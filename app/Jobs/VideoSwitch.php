<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Task;

class VideoSwitch implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $task;

    /**
     * Create a new job instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->task->status !== Task::WAITING) {
            return;
        }
        $this->updateTaskStatus(Task::RUNNING);
        $cmd = $this->generateCmdFromTask();
        info("exec: $cmd");
        system($cmd, $exit_code);
        if($exit_code === 0) {
            $this->updateTaskStatus(Task::FINISH);
        } else {
            $this->updateTaskStatus(Task::ERROR);
        }
    }

    private function generateCmdFromTask() {
        $payload = $this->task['payload'];
        $videoDir = $payload['video_dir'];
        $outputDir = $this->task->outputDir();
//        if(! file_exists($outputDir)) {
//            mkdir($outputDir, 0777, true);
//        }
        $snDir = $this->task->configDir();
        $ringRectifyFile = "/home/tpys/dataset/20160808_tianlianhu_done/20160808_lanqiu_gopro/config/ring_rectify.xml";
        $cameraSettingFile = "/home/tpys/dataset/20160808_tianlianhu_done/20160808_lanqiu_gopro/config/camera_setting.xml";
        $topRectifyFile = "/home/tpys/dataset/20160808_tianlianhu_done/20160808_lanqiu_gopro/config/top_rectify.xml";
        $bottomRectifyFile = "/home/tpys/dataset/20160808_tianlianhu_done/20160808_lanqiu_gopro/config/bottom_rectify.xml";
        $mixRectifyFile = "/home/tpys/dataset/20160808_tianlianhu_done/20160808_lanqiu_gopro/config/mix_rectify.xml";
        $enableTop = $payload['enable_top'];
        $enableBottom = $payload['enable_bottom'];
        $enableColorAdjust = $payload['enable_coloradjust'];
        $startFrame = $payload['start_frame'];
        $endFrame = $payload['end_frame'];
        $time_alignment = implode('_', $payload['time_alignment']);
        $cmdFormat = config('task.exec_path')." ".
                      "-video_dir %s ".
                      "-output_dir %s ".
                      "-ring_rectify_file %s ".
                      "-top_rectify_file %s ".
                      "-bottom_rectify_file %s ".
                      "-mix_rectify_file %s ".
                      "-camera_setting_file %s ".
                      "-enable_top %s ".
                      "-enable_bottom %s ".
                      "-enable_coloradjust %s ".
                      "-start_frame %s ".
                      "-end_frame %s ".
                      "-time_alignment %s ";
        $cmd = sprintf($cmdFormat, $videoDir, $outputDir,
                      $ringRectifyFile, $topRectifyFile, $bottomRectifyFile, $mixRectifyFile, $cameraSettingFile,
                      $enableTop, $enableBottom, $enableColorAdjust, $startFrame, $endFrame, $time_alignment);
        return $cmd;
    }

    private function updateTaskStatus($status) {
        $this->task->status = $status;
        $this->task->processed = 100;
        $this->task->save();
        if ($this->task->parent_id !== 0) {
            $this->updateParentStatus();
        }
    }

    private function updateParentStatus() {
        $parentTask = $this->task->parentTask()->first();
        if($this->task->status !== Task::ERROR && $this->task->staus !== Task::FINISH) {
            if($this->task->status === Task::RUNNING && $parentTask->status !== Task::RUNNING) {
                $parentTask->status = Task::RUNNING;
                $parentTask->save();
                return;
            }
        }
        $subTasks = $parentTask->subTasks()->get();
        $finished = 0;
        $failed = 0;
        $running = 0;
        foreach($subTasks as $task) {
            if($task->status === Task::FINISH) {
                $finished += 1;
            } elseif($task->status === Task::ERROR) {
                $failed += 1;
            } else {
                $running += 1;
            }
        }
        if(count($subTasks) === $finished) {
            $parentTask->status = Task::FINISH;
            $parentTask->save();
        } elseif(count($subTasks) === $failed + $finished) {
            $parentTask->status = Task::ERROR;
            $parentTask->save();
        }
    }
}
