#!/bin/bash

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

docker build --no-cache -t app-image "$SCRIPT_DIR/../image/"