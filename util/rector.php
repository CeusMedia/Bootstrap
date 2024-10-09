<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->paths([
		__DIR__ . '/../src',
	]);

	// register a single rule
//	$rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

	// define sets of rules
	$rectorConfig->sets([
		LevelSetList::UP_TO_PHP_83,
	]);

	$skipFolders	= [];
	$skipFiles		= [];
	$skipRules		= [
		// Set 8.0
		ClassPropertyAssignToConstructorPromotionRector::class,
		// Set 8.3
		AddOverrideAttributeToOverriddenMethodsRector::class
	];
	$rectorConfig->skip(array_merge($skipFolders, $skipFiles, $skipRules));
};