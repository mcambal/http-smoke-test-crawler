<?php

namespace Tests\Unit\Handler;

use App\Contract\Mailer;
use App\Contract\WebCrawler;
use App\Entity\Simple\CrawlerConfiguration;
use App\Entity\Simple\OutputConfiguration;
use App\Handler\CrawlHandler;
use App\Output\Context\OutputContext;
use App\Collection\FileCollection;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CrawlHandlerTest extends TestCase
{
    private CrawlHandler $crawlHandler;
    private MockObject $mockCrawler;
    private MockObject $mockOutputContext;
    private MockObject $mockMailer;

    protected function setUp(): void
    {
        $this->mockCrawler = $this->createMock(WebCrawler::class);
        $this->mockOutputContext = $this->createMock(OutputContext::class);
        $this->mockMailer = $this->createMock(Mailer::class);
        
        $this->crawlHandler = new CrawlHandler(
            $this->mockCrawler,
            $this->mockOutputContext,
            $this->mockMailer
        );
    }

    public function testCrawlSetsOutputProcessorAndFilterStrategies(): void
    {
        $baseUrl = 'https://example.com';
        $crawlerConfiguration = $this->createMock(CrawlerConfiguration::class);
        $outputConfiguration = $this->createMock(OutputConfiguration::class);
        
        $outputProcessors = ['processor1', 'processor2'];
        $outputFilters = ['filter1', 'filter2'];
        
        $outputConfiguration->expects($this->once())
            ->method('getOutputProcessors')
            ->willReturn($outputProcessors);
            
        $outputConfiguration->expects($this->once())
            ->method('getOutputFilters')
            ->willReturn($outputFilters);

        $this->mockOutputContext->expects($this->once())
            ->method('setOutputProcessorStrategy')
            ->with($outputProcessors);
            
        $this->mockOutputContext->expects($this->once())
            ->method('setOutputFilterStrategy')
            ->with($outputFilters);

        // Mock the file collection for resetLogs
        $mockFileCollection = $this->createMock(FileCollection::class);
        $mockFileCollection->expects($this->once())
            ->method('getNotEmptyFiles')
            ->willReturn([]);
            
        $this->mockOutputContext->expects($this->once())
            ->method('getLogFiles')
            ->willReturn($mockFileCollection);

        $this->mockCrawler->expects($this->once())
            ->method('setOutputContext')
            ->with($this->mockOutputContext)
            ->willReturnSelf();
            
        $this->mockCrawler->expects($this->once())
            ->method('setConfiguration')
            ->with($crawlerConfiguration)
            ->willReturnSelf();
            
        $this->mockCrawler->expects($this->once())
            ->method('crawl')
            ->with($baseUrl);

        $this->crawlHandler->crawl($baseUrl, $crawlerConfiguration, $outputConfiguration);
    }

    public function testSendEmailReportSetsMailerPropertiesAndSendsEmail(): void
    {
        $from = 'test@example.com';
        $subject = 'Test Subject';
        $emails = ['recipient1@example.com', 'recipient2@example.com'];
        $body = '<h1>Test Body</h1>';
        $attachments = ['/path/to/file1.log', '/path/to/file2.log'];

        $mockFileCollection = $this->createMock(FileCollection::class);
        $mockFileCollection->expects($this->once())
            ->method('getNotEmptyFiles')
            ->willReturn($attachments);

        $this->mockOutputContext->expects($this->once())
            ->method('getLogFiles')
            ->willReturn($mockFileCollection);

        $this->mockMailer->expects($this->once())
            ->method('setFrom')
            ->with($from)
            ->willReturnSelf();
            
        $this->mockMailer->expects($this->once())
            ->method('setSubject')
            ->with($subject)
            ->willReturnSelf();
            
        $this->mockMailer->expects($this->once())
            ->method('setTo')
            ->with($emails)
            ->willReturnSelf();
            
        $this->mockMailer->expects($this->once())
            ->method('setAttachments')
            ->with($attachments)
            ->willReturnSelf();
            
        $this->mockMailer->expects($this->once())
            ->method('sendHtml')
            ->with($body);

        $this->crawlHandler->sendEmailReport($from, $subject, $emails, $body);
    }

    public function testResetLogsDeletesNonEmptyFiles(): void
    {
        // Create temporary test files
        $tempFile1 = tempnam(sys_get_temp_dir(), 'test_log_1');
        $tempFile2 = tempnam(sys_get_temp_dir(), 'test_log_2');
        
        file_put_contents($tempFile1, 'test content 1');
        file_put_contents($tempFile2, 'test content 2');
        
        $this->assertTrue(file_exists($tempFile1));
        $this->assertTrue(file_exists($tempFile2));

        $mockFileCollection = $this->createMock(FileCollection::class);
        $mockFileCollection->expects($this->once())
            ->method('getNotEmptyFiles')
            ->willReturn([$tempFile1, $tempFile2]);

        $this->mockOutputContext->expects($this->once())
            ->method('getLogFiles')
            ->willReturn($mockFileCollection);

        $crawlerConfiguration = $this->createMock(CrawlerConfiguration::class);
        $outputConfiguration = $this->createMock(OutputConfiguration::class);
        
        $outputConfiguration->method('getOutputProcessors')->willReturn([]);
        $outputConfiguration->method('getOutputFilters')->willReturn([]);

        $this->mockCrawler->method('setOutputContext')->willReturnSelf();
        $this->mockCrawler->method('setConfiguration')->willReturnSelf();
        $this->mockCrawler->method('crawl');

        // Call crawl which internally calls resetLogs
        $this->crawlHandler->crawl('https://example.com', $crawlerConfiguration, $outputConfiguration);

        // Verify files were deleted
        $this->assertFalse(file_exists($tempFile1));
        $this->assertFalse(file_exists($tempFile2));
    }
}