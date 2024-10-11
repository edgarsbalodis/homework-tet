# Define the Docker service name for server3
SERVER3_CONTAINER=server3

# Default target when running `make` without arguments
all: migrate

# Migrate for server3
migrate:
	@echo "Running migrations for $(SERVER3_CONTAINER)..."
	docker exec $(SERVER3_CONTAINER) php artisan migrate

