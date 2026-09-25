<?php

declare(strict_types=1);

namespace Kitodo\Dlf\Tests\Unit\ViewHelpers;

use Kitodo\Dlf\ViewHelpers\LinkViewHelper;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LinkViewHelperTest extends UnitTestCase
{
    #[Test]
    public function resolveRequestReturnsNullWithoutRequestSources(): void
    {
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::once())->method('getRequest')->willReturn(null);
        $renderingContext->expects(self::once())->method('hasAttribute')->willReturn(false);

        $viewHelper = $this->createViewHelper();

        self::assertNull($viewHelper->resolveRequestPublic($renderingContext));
    }

    #[Test]
    public function resolveRequestFallsBackToRequestAttribute(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::once())->method('getRequest')->willReturn(null);
        $renderingContext->expects(self::once())->method('hasAttribute')->with(ServerRequestInterface::class)->willReturn(true);
        $renderingContext->expects(self::once())->method('getAttribute')->with(ServerRequestInterface::class)->willReturn($request);

        $viewHelper = $this->createViewHelper();

        self::assertSame($request, $viewHelper->resolveRequestPublic($renderingContext));
    }

    private function createViewHelper(): LinkViewHelper
    {
        return new class extends LinkViewHelper {
            public function resolveRequestPublic(RenderingContextInterface $renderingContext): ?RequestInterface
            {
                return $this->resolveRequest($renderingContext);
            }
        };
    }
}
