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
        $tokens = token_get_all($contents);
        $renderMethods = 0;

        $tokenCount = count($tokens);
        for ($index = 0; $index < $tokenCount; $index++) {
            $token = $tokens[$index];
            if (!is_array($token) || $token[0] !== T_FUNCTION) {
                continue;
            }

            for ($nameIndex = $index + 1; $nameIndex < $tokenCount; $nameIndex++) {
                $nameToken = $tokens[$nameIndex];
                if (!is_array($nameToken)) {
                    continue;
                }
                if ($nameToken[0] === T_STRING) {
                    if (strtolower($nameToken[1]) === 'render') {
                        $renderMethods++;
                    }
                    break;
                }
            }
        }

        self::assertSame(1, $renderMethods, 'Expected one render() method in ' . $relativePath);
    }
}
