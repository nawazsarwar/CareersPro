#!/bin/bash

set -e

IMAGE_NAME="clipwise_image"
CONTAINER_NAME="clipwise_container"
ENV_FILE=".env.docker"

# Check for --rebuild flag
REBUILD=false
for arg in "$@"; do
  if [ "$arg" == "--rebuild" ]; then
    REBUILD=true
  fi
done

# Check if .env.docker exists
if [ ! -f "$ENV_FILE" ]; then
  echo "Error: $ENV_FILE not found in project root. Please create it before running this script."
  exit 1
fi

# Remove old Docker image if --rebuild is specified
if [ "$REBUILD" = true ]; then
  if docker images | grep -q "$IMAGE_NAME"; then
    echo "Removing old Docker image: $IMAGE_NAME"
    docker rmi -f $IMAGE_NAME || true
  fi
fi

# Build the Docker image
docker build -t $IMAGE_NAME .

# Stop and remove any existing container with the same name
if [ $(docker ps -aq -f name=$CONTAINER_NAME) ]; then
    docker stop $CONTAINER_NAME || true
    docker rm $CONTAINER_NAME || true
fi

# Run the Docker container with host networking for host.docker.internal support (Mac/Windows)
docker run --name $CONTAINER_NAME --env-file $ENV_FILE -p 8180:80 -p 9000:9000 \
  --add-host=host.docker.internal:host-gateway \
  $IMAGE_NAME
