#!/bin/sh

# This script downloads and creates the below files:
#   - Downloads the latest "JSqueeze" source code.
#   - Creates version info file of JSqueeze, Alpine as PHP constants.
#   - Creates repository URL file of PHP-Alpine for APK in Alpine.
# ===================================================================
# - From: https://github.com/nicolas-grekas/JSqueeze/releases
# - Note: This script must be called inside container on build stage. See the
#         Dockerfile.

# Include variables of OS info
source /etc/os-release

# Fetch latest version number of the release.
# -------------------------------------------
# Since the "jsqueeze" repo doesn't provide releases info in the GitHub API v3
# releases endpoint, we'll get them from the tags endpoint as a workaround.
name_user_github='tchwork'
name_repo_github='jsqueeze'
url_api_base='https://api.github.com/repos'
url_api_endpoint_tags="/${name_user_github}/${name_repo_github}/tags"

version=$(curl -s ${url_api_base}${url_api_endpoint_tags} \
  -H "Accept: application/vnd.github.v3.full+json" \
  | grep jsqueeze \
  | grep tarball_url \
  | head -1 \
  | awk -F '[:/"]' '{ print $13 }'
)
echo $version | grep -E "^v[0-9\.]+" > /dev/null
if [ $? -ne 0 ]; then
  echo 'Fail to fetch version number.' >&2
  exit 1
fi

# Download JSqueeze latest release and create version info file for PHP.
# ----------------------------------------------------------------------
name_file_tar="${version}.tar.gz"
url_file_tar="https://github.com/${name_user_github}/${name_repo_github}/archive/${name_file_tar}"

curl --verbose --location --output $name_file_tar $url_file_tar && \
tar -xvf $name_file_tar && \
cp ./jsqueeze*/src/JSqueeze.php /JSqueeze.php &&
echo -e "<?php\n\nconst VERSION_APP='JSqueeze ${version}';\nconst VERSION_OS='${NAME} v${VERSION_ID}';\n" > /app-release.php.inc

# Create URL file of PHP-Alpine project to add in APK repository list.
# --------------------------------------------------------------------

name_subject='php-alpine'

# Get Repositories
name_repo=$(curl -s "https://api.bintray.com/repos/${name_subject}/" | jq -r '.[].name' | tail -1)

# Get Packages
name_package=$(curl -s "https://api.bintray.com/repos/${name_subject}/${name_repo}/packages" | jq -r '.[].name' | tail -1)

# Generate URL and file
# Format: @php https://dl.bintray.com/:subject/:repo/:package
# Sample: @php https://dl.bintray.com/php-alpine/v3.9/php-7.3
url_repo_apk_add="@php https://dl.bintray.com/${name_subject}/${name_repo}/${name_package}"

echo $url_repo_apk_add > /etc_apk_repositories
