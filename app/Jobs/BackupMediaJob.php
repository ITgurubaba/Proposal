<?php

namespace App\Jobs;

use App\Models\BackupImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Files\Disk;

class BackupMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public BackupImage $backupImage;

    public function __construct($backupImage)
    {
        $this->backupImage = $backupImage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->backupImage->status == 0)
        {
            $oldDisk = Storage::disk('public_main');
            $disk = Storage::disk('spaces');

            if($oldDisk->exists($this->backupImage->file))
            {
                $disk->put($this->backupImage->path,$oldDisk->get($this->backupImage->file),'public');

                $this->backupImage->status = 1;
                $this->backupImage->save();

            }

        }
    }
}
