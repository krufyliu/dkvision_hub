@extends('layouts.admin')

@section('body')
    <div class="panel panel-default">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">控制台</a></li>
                <li><a href="{{ url('/tasks') }}">任务管理</a></li>
                <li class="active">任务详情</li>
            </ol>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <th colspan="2">基本信息</th>
                        </tr>
                        <tr>
                          <td class="text-right">任务编号</td>
                          <td>{{ $task->id }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">任务名</td>
                          <td>{{ $task->title }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">创建时间</td>
                          <td>{{ $task->created_at }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">更新时间</td>
                          <td>{{ $task->updated_at }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">任务类型</td>
                          <td>{{ $task->payload['task_type'] }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">UUID</td>
                          <td>{{ $task->uuid }}</td>
                        </tr>
                        <tr>
                          <th colspan="2">参数信息</th>
                        </tr>
                        <tr>
                          <td class="text-right">视频目录</td>
                          <td>{{ $task->payload['video_dir'] }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">起始帧-结束桢</td>
                          <td>{{ $task->payload['start_frame'] }} - {{ $task->payload['end_frame'] }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">帧同步</td>
                          <td>{{ implode(', ', $task->payload['time_alignment']) }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">顶部</td>
                          <td>{{ $task->payload['enable_top'] }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">底部</td>
                          <td>{{ $task->payload['enable_bottom'] }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">颜色调整</td>
                          <td>{{ $task->payload['enable_coloradjust'] }}</td>
                        </tr>
                        <tr>
                          <td class="text-right">质量</td>
                          <td>{{ $task->payload['quality'] }}</td>
                        </tr>
                        <tr>
                          <th colspan="2">子任务信息</th>
                        </tr>
                        <tr>
                          <td class="text-right">总数</td>
                          <td>10</td>
                        </tr>
                        <tr>
                          <td class="text-right">完成</td>
                          <td>2</td>
                        </tr>
                        <tr>
                          <td class="text-right">运行中</td>
                          <td>2</td>
                        </tr>
                        <tr>
                          <td class="text-right">等待中</td>
                          <td>7</td>
                        </tr>
                        <tr>
                          <td class="text-right">失败</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td class="text-right">未知</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td class="text-right">进度预览</td>
                          <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" style="width: 5%">
                                         <span class="sr-only">35% Complete (success)</span>
                                    </div>
                                    <div class="progress-bar progress-bar-info" style="width: 5%">
                                         <span class="sr-only">35% Complete (info)</span>
                                    </div>
                                    <div class="progress-bar progress-bar-primary" style="width: 5%">
                                         <span class="sr-only">35% Complete (primary)</span>
                                    </div>
                                    <div class="progress-bar progress-bar-danger" style="width: 0%">
                                        <span class="sr-only">10% Complete (danger)</span>
                                    </div>
                                    <div class="progress-bar progress-bar-warning" style="width: 0%">
                                        <span class="sr-only">20% Complete (warning)</span>
                                    </div>
                                </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
