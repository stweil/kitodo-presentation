<?php

/**
 * (c) Kitodo. Key to digital objects e.V. <contact@kitodo.org>
 *
 * This file is part of the Kitodo and TYPO3 projects.
 *
 * @license GNU General Public License version 3 or later.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Kitodo\Dlf\Tests\Unit\ViewHelpers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class RenderMethodDeclarationTest extends UnitTestCase
{
    /**
     * @return array<string, array<int, string>>
     */
    public static function renderMethodFilesProvider(): array
    {
        return [
            'is-array' => ['Classes/ViewHelpers/IsArrayViewHelper.php'],
            'js-footer' => ['Classes/ViewHelpers/JsFooterViewHelper.php'],
            'metadata-wrap-variable' => ['Classes/ViewHelpers/MetadataWrapVariableViewHelper.php'],
        ];
    }

    #[Test]
    #[DataProvider('renderMethodFilesProvider')]
    public function renderMethodIsDeclaredOnlyOnce(string $relativePath): void
    {
        $absolutePath = dirname(__DIR__, 3) . '/' . $relativePath;
        $contents = (string) file_get_contents($absolutePath);

        preg_match_all('/public function render\\s*\\(/', $contents, $matches);

        self::assertCount(1, $matches[0], 'Expected one render() method in ' . $relativePath);
    }
}
