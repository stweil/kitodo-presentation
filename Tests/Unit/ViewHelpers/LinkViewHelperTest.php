<?php

declare(strict_types=1);

namespace Kitodo\Dlf\Tests\Unit\ViewHelpers;

use Kitodo\Dlf\ViewHelpers\LinkViewHelper;
use PHPUnit\Framework\Attributes\Test;
use ReflectionProperty;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LinkViewHelperTest extends UnitTestCase
{
    #[Test]
    public function renderReturnsEmptyStringWhenRenderingContextHasNoRequest(): void
    {
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::once())->method('getRequest')->willReturn(null);

        $viewHelper = new LinkViewHelper();
        $this->injectProperty($viewHelper, 'renderingContext', $renderingContext);

        self::assertSame('', $viewHelper->render());
    }

    private function injectProperty(object $object, string $propertyName, mixed $value): void
    {
        $property = new ReflectionProperty($object, $propertyName);
        $property->setValue($object, $value);
    }
}
