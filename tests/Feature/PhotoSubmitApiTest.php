<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_upload_picture()
    {
        // S3ではなくテスト用ストレージを使用する
        // → storage/framework/testing
        Storage::fake('sftp');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                // ダミーファイルを作成して送信
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスは201(CREATED)である
        $response->assertStatus(201);

        $photo = Photo::first();

        // 写真のIDが12桁のランダムな文字列である
        $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $photo->id);

        // DBに挿入されたファイル名のファイルがストレージに保存されている
        Storage::cloud()->assertExists($photo->filename);
    }

    /**
     * @test
     */
    public function should_not_save_file_as_database_error()
    {
        // DBエラーを起こす
        Schema::drop('photos');

        Storage::fake('sftp');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                // ダミーファイルを作成して送信
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスが500(INTERNAL SERVER ERROR)である
        $response->assertStatus(500);

        // ストレージにファイルが保存されていない
        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    /**
     * @test
     */
    public function should_not_insert_to_db_as_file_save_error()
    {
        // ストレージをモックして保存時にエラーを起こす
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                // ダミーファイルを作成して送信
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスが500(INTERNAL SERVER ERROR)である
        $response->assertStatus(500);

        // データベースに何も挿入されていない
        $this->assertEmpty(Photo::all());
    }
}
