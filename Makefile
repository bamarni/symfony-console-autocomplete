BATS_GIT := https://github.com/sstephenson/bats.git
BATS_TMP := /tmp/bats
COMPOSER_COMMAND ?= composer
COMPOSER_OPTIONS ?= --prefer-source
GIT ?= git
PHP ?= php

COMPOSER_EXECUTABLE := $(shell command -v $(COMPOSER_COMMAND) 2> /dev/null)
COMPOSER_CMD := $(PHP) $(COMPOSER_EXECUTABLE)
export COMPOSER_VENDOR_DIR := vendor-$(PHP).cache
export COMPOSER := composer-$(PHP).cache.json

export PATH := $(PWD)/bin:$(dir $(BATS_TMP))local/bin:$(PWD)/tests/fixtures:$(PATH)


.PHONY : all
all : install test

.PHONY : test
test : $(dir $(BATS_TMP))local/bin/bats vendor
	bats tests

.PHONY : install
install : $(dir $(BATS_TMP))local/bin/bats vendor
	@$(PHP) --version | head -1
	@$(COMPOSER_CMD) --version
	@bats --version

$(dir $(BATS_TMP))local/libexec/bats $(dir $(BATS_TMP))local/bin/bats : $(BATS_TMP)/.git/HEAD
	mkdir -p $(dir $(BATS_TMP))local
	bash $(BATS_TMP)/install.sh $(dir $(BATS_TMP))local
	bats --version
	touch $(@)

$(BATS_TMP)/.git/HEAD :
	$(GIT) clone $(BATS_GIT) $(BATS_TMP)

.PHONY : vendor
vendor : $(COMPOSER) $(COMPOSER_VENDOR_DIR)/composer/installed.json

composer-%.cache.json : composer.json
	rm -f $@
	cp $< $@

composer.lock : $(COMPOSER_VENDOR_DIR)/composer/installed.json
	touch $@

$(COMPOSER_VENDOR_DIR)/composer/installed.json : composer.json
	$(COMPOSER_CMD) update $(COMPOSER_OPTIONS)
	touch $@

.PHONY : clean
clean :
	rm -rf -- $(BATS_TMP) $(dir $(BATS_TMP))local
	git clean -ffx vendor-*.cache* composer-*.cache*
