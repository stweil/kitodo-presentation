#!/usr/bin/env bash
# Run PHPStan locally, mirroring the CI workflow in .github/workflows/phpstan.yml:
#   12.4: cp composer.lock.v12 composer.lock && composer install --no-scripts --ignore-platform-reqs
#   13.4: composer update --no-scripts --ignore-platform-reqs --with=typo3/cms-core:^13.4
# then: vendor/bin/phpstan analyse --configuration=.github/phpstan_<version>.neon
#
# Usage: Build/Test/runPhpstan.sh -t <12.4|13.4>
set -euo pipefail

DLF_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$DLF_ROOT"

TYPO3_VERSION=""
while getopts ":t:h" opt; do
    case ${opt} in
        t) TYPO3_VERSION=${OPTARG} ;;
        h) echo "Usage: $0 -t <12.4|13.4>"; exit 0 ;;
        *) echo "Unknown option: -$OPTARG" >&2; exit 1 ;;
    esac
done

case ${TYPO3_VERSION} in
    12.4|13.4) ;;
    *) echo "Unsupported TYPO3 version: '${TYPO3_VERSION:-}'" >&2; echo "Usage: $0 -t <12.4|13.4>" >&2; exit 1 ;;
esac

if [ ! -f ".github/phpstan_${TYPO3_VERSION}.neon" ]; then
    echo "No PHPStan configuration for TYPO3 ${TYPO3_VERSION}" >&2
    exit 1
fi

echo "Installing dependencies for TYPO3 ${TYPO3_VERSION}..."
if [ "${TYPO3_VERSION}" = "12.4" ]; then
    cp composer.lock.v12 composer.lock
    composer install --no-scripts --ignore-platform-reqs
else
    composer update --no-scripts --ignore-platform-reqs --with=typo3/cms-core:"^${TYPO3_VERSION}"
fi

echo "Running PHPStan for TYPO3 ${TYPO3_VERSION}..."
vendor/bin/phpstan analyse --configuration=".github/phpstan_${TYPO3_VERSION}.neon"
