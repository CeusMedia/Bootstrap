test-syntax:
	@echo "Checking syntax..."
	@find src -type f -print0 | xargs -0 -n1 xargs php -l

