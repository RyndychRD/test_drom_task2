<?php

use PHPUnit\Framework\TestCase;
use Poman\TestDrom\Task2\client\CommentClient;
use Poman\TestDrom\Task2\client\CommentClientInterface;
use Poman\TestDrom\Task2\model\Comment;
use Poman\TestDrom\Task2\service\CommentService;
use Poman\TestDrom\Task2\throwable\CommentNotFound;
use Poman\TestDrom\Task2\throwable\RequestFormatException;
use Poman\TestDrom\Task2\throwable\ResponseFormatException;
use Poman\TestDrom\Task2\throwable\ServiceError;

final class CommentUnitTest extends TestCase
{

    protected CommentClientInterface $httpClient;

    public function setUp(): void
    {
        $this->httpClient = $this->createStub(CommentClient::class);
    }

    public function testServiceDown()
    {
        $this->httpClient->method('getComments')->willThrowException(new ServiceError());
        $this->expectException(ServiceError::class);
        $client = new CommentService($this->httpClient);
        $client->getComments();
    }

    public function testGetCommentsEmptyContent()
    {
        $this->httpClient->method('getComments')->willReturn([]);
        $client = new CommentService($this->httpClient);
        $this->assertSame([], $client->getComments());
    }

    public function testGetComments3Comments()
    {
        $response = [
            [
                'id'   => 1,
                'text' => 'text1',
                'name' => 'name1',
            ],
            [
                'id'   => 2,
                'text' => 'text2',
                'name' => 'name2',
            ],
            [
                'id'   => 3,
                'text' => 'text3',
                'name' => 'name3',
            ],
        ];
        $expected = [
            new Comment(1, 'name1', 'text1'),
            new Comment(2, 'name2', 'text2'),
            new Comment(3, 'name3', 'text3'),
        ];
        $this->httpClient->method('getComments')->willReturn($response);
        $client = new CommentService($this->httpClient);
        $this->assertEqualsCanonicalizing($expected, $client->getComments());
    }

    public function testGetCommentsResponseWithDifferentFormat()
    {
        $response = [
            [
                'id'                => 1,
                'text_another_name' => 'text1',
                'name'              => 'name1',
            ],
        ];
        $expected = [
            new Comment(1, 'name1', 'text1'),
        ];
        $this->httpClient->method('getComments')->willReturn($response);
        $this->expectException(ResponseFormatException::class);
        $client = new CommentService($this->httpClient);
        $client->getComments();
    }

    public function testPostComment()
    {
        $response = [
            'id'   => 1,
            'name' => 'name1',
            'text' => 'text1',
        ];
        $expected = new Comment(1, 'name1', 'text1');
        $this->httpClient->method('postComment')->willReturn($response);
        $client = new CommentService($this->httpClient);
        $this->assertEquals($expected, $client->postComment('name1', 'text1'));
    }

    public function testPostCommentRequestFormatError()
    {
        $this->httpClient->method('postComment')->willThrowException(new RequestFormatException());
        $this->expectException(RequestFormatException::class);
        $client = new CommentService($this->httpClient);
        $client->postComment('name1', 'text1');
    }

    public function testPostCommentsResponseWithDifferentFormat()
    {
        $response = [
            [
                'id'                => 1,
                'text_another_name' => 'text1',
                'name'              => 'name1',
            ],
        ];
        $expected = [
            new Comment(1, 'name1', 'text1'),
        ];
        $this->httpClient->method('postComment')->willReturn($response);
        $this->expectException(ResponseFormatException::class);
        $client = new CommentService($this->httpClient);
        $client->postComment('new name', 'new text');
    }

    public function testUpdateCommentOneField()
    {
        $response = [
            'id'   => 1,
            'text' => 'text_updated',
            'name' => 'name1',
        ];
        $expected = new Comment(1, 'name1', 'text_updated');
        $this->httpClient->method('updateComment')->willReturn($response);
        $client = new CommentService($this->httpClient);
        $this->assertEquals($expected, $client->updateComment(1, text: 'text_updated'));

    }

    public function testUpdateCommentAllFields()
    {
        $response = [
            'id'   => 1,
            'text' => 'text_updated',
            'name' => 'name_updated',
        ];
        $expected = new Comment(1, 'name_updated', 'text_updated');
        $this->httpClient->method('updateComment')->willReturn($response);
        $client = new CommentService($this->httpClient);
        $this->assertEquals($expected, $client->updateComment(1, 'name_updated', 'text_updated'));

    }

    public function testCommentToUpdateNotFound()
    {

        $this->httpClient->method('updateComment')->willThrowException(new CommentNotFound());
        $this->expectException(CommentNotFound::class);
        $client = new CommentService($this->httpClient);
        $client->updateComment(1, 'name1', 'text1');

    }

    public function testUpdateCommentsResponseWithDifferentFormat()
    {
        $response = [
            [
                'id'                => 1,
                'text_another_name' => 'text1',
                'name'              => 'name1',
            ],
        ];
        $expected = [
            new Comment(1, 'name1', 'text1'),
        ];
        $this->httpClient->method('updateComment')->willReturn($response);
        $this->expectException(ResponseFormatException::class);
        $client = new CommentService($this->httpClient);
        $client->updateComment(1, 'new name', 'new text1');
    }

    public function testUpdateCommentResponseFormatError()
    {
        $this->httpClient->method('updateComment')->willThrowException(new RequestFormatException());
        $this->expectException(RequestFormatException::class);
        $client = new CommentService($this->httpClient);
        $client->updateComment(1, 'name1', 'text1');
    }
}