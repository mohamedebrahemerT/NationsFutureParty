<?php

use App\Models\ClientDocument;
use App\Models\DiscussionFile;
use App\Models\EmployeeDocument;
use App\Models\LeadFiles;
use App\Models\ProjectFile;
use App\Models\TaskFile;
use App\Models\TicketFile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $storage = \App\Models\StorageSetting::where('filesystem', 'aws')->first();

        if ($storage) {
            $storage->filesystem = 'aws_s3';
            $storage->save();
        }

        Schema::create('file_storage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->string('filename');
            $table->string('type', 50)->nullable();
            $table->unsignedInteger('size');
            $table->enum('storage_location', ['local', 'aws_s3'])->default('local');
            $table->timestamps();
        });

        $settings = storage_setting();
        $storageLocation = $settings->filesystem == 'aws_s3' ? 'aws_s3' : 'local';

        $files = [
            ['model' => TicketFile::all(), 'file_path' => TicketFile::FILE_PATH],
            ['model' => ClientDocument::all(), 'file_path' => ClientDocument::FILE_PATH],
            ['model' => \App\Models\ContractFile::all(), 'file_path' => \App\Models\ContractFile::FILE_PATH],
            ['model' => DiscussionFile::all(), 'file_path' => DiscussionFile::FILE_PATH],
            ['model' => EmployeeDocument::all(), 'file_path' => EmployeeDocument::FILE_PATH],
            ['model' => \App\Models\EstimateItemImage::all(), 'file_path' => \App\Models\EstimateItemImage::FILE_PATH],
            ['model' => \App\Models\Expense::all(), 'file_path' => \App\Models\Expense::FILE_PATH],
            ['model' => \App\Models\InvoiceItemImage::all(), 'file_path' => \App\Models\InvoiceItemImage::FILE_PATH],
            ['model' => LeadFiles::all(), 'file_path' => LeadFiles::FILE_PATH],
            ['model' => \App\Models\Payment::all(), 'file_path' => \App\Models\Payment::FILE_PATH],
            ['model' => \App\Models\Product::all(), 'file_path' => \App\Models\Product::FILE_PATH],
            ['model' => \App\Models\ProductFiles::all(), 'file_path' => \App\Models\ProductFiles::FILE_PATH],
            ['model' => ProjectFile::all(), 'file_path' => ProjectFile::FILE_PATH],
            ['model' => \App\Models\ProposalItemImage::all(), 'file_path' => \App\Models\ProposalItemImage::FILE_PATH],
            ['model' => \App\Models\RecurringInvoiceItemImage::all(), 'file_path' => \App\Models\RecurringInvoiceItemImage::FILE_PATH],
            ['model' => \App\Models\SubTaskFile::all(), 'file_path' => \App\Models\SubTaskFile::FILE_PATH],
            ['model' => TaskFile::all(), 'file_path' => TaskFile::FILE_PATH],
            ['model' => \App\Models\UserchatFile::all(), 'file_path' => \App\Models\UserchatFile::FILE_PATH],

        ];


        foreach ($files as $file) {
            $this->fileStore($file['model'], $file['file_path'], $storageLocation);
        }

    }

    private function fileStore($files, $folder, $storageType = 'local')
    {

        foreach ($files as $file) {
            try {
                if (is_null($file->hashname)) {
                    continue;
                }

                $fileStorage = new \App\Models\FileStorage();
                $fileStorage->filename = $file->hashname;
                $fileStorage->size = $file->size;
                $fileStorage->path = $folder;
                $fileStorage->storage_location = $storageType;
                $fileStorage->created_at = $file->created_at;
                $fileStorage->save();
            } catch (\Exception $e) {
                logger('Not Found');
            }

        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_storage');
    }

}
