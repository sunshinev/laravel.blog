@extends('admin.main')
@section('right')
    <div class="row">
        <div class="col-md-6 form-inline">
            <input type="text" id="parent_title" class="form-control" placeholder="填写一级菜单名称">
            <button class="btn btn-info" onclick="Category.addParent()">确认添加</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div style="width:200px;float:left;margin-right:20px;" class="sun">
            <ul class="list-group">
                @if ($root_list->count())
                    @foreach($root_list as $item)
                        @if ($root_node && $root_node->id == $item->id)
                        <li class="list-group-item active" onclick="location.href='{{ URL::to('admin/category/manage/0_'.$item->id) }}'">
                            {{$item->title}}
                        </li>
                        @if($current_node->id == $item->id)
                        <li class="list-group-item">
                            <button class="btn btn-sm btn-info" onclick="$('#addModal').modal('show')"><span class="glyphicon glyphicon-plus"></span></button>
                            <button class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-remove"></span></button>
                            <button class="btn btn-sm btn-success"><span class="glyphicon glyphicon-pushpin"></span></button>
                        </li>
                        @endif
                        @else
                        <li class="list-group-item" onclick="location.href='{{ URL::to('admin/category/manage/0_'.$item->id) }}'">
                            {{$item->title}}
                        </li>
                        @endif
                    @endforeach
                @else
                    <li class="list-group-item">
                        请添加父级栏目!!!
                    </li>
                @endif
            </ul>
        </div>
        @if ($category_list)
            @foreach($category_list as $level_list)
            <div style="width:200px;float:left;margin-right:20px;" class="sun">
                <ul class="list-group">
                    @foreach($level_list as $item)
                        @if ($item->is_current)
                            <li class="list-group-item active" onclick="location.href='{{ URL::to('admin/category/manage/0_'.$item->id) }}'">
                                {{ $item->title }}
                            </li>
                        @if($current_node->id == $item->id)
                            <li class="list-group-item">
                                <button class="btn btn-sm btn-info" onclick="$('#addModal').modal('show')"><span class="glyphicon glyphicon-plus"></span></button>
                                <button class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-remove"></span></button>
                                <button class="btn btn-sm btn-success"><span class="glyphicon glyphicon-pushpin"></span></button>
                            </li>
                        @endif
                        @else
                            <li class="list-group-item" onclick="location.href='{{ URL::to('admin/category/manage/0_'.$item->id) }}'">
                                {{ $item->title }}
                            </li>
                        @endif

                    @endforeach
                </ul>
            </div>
            @endforeach
        @endif
    </div>
    @if ($current_node)
    <!-- 模态框BEGIN -->
    <div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">新建栏目到“{{ $current_node->title }}”下</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="child_title" placeholder="栏目名称">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-success" onclick="Category.addChild('{{ $current_node->id }}')">确认保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @endif
    <!-- 模态框 end -->
    <script>
        var Category = (function() {

            // 通用添加方法
            var _add = function(pid,title) {
                $.ajax({
                    type:'post',
                    data:{
                        'title':title,
                        'pid':pid
                    },
                    url:"{{ asset('admin/category/add') }}",
                    headers:{
                        'X-CSRF-TOKEN':"{{ csrf_token() }}"
                    },
                    success:function(d,s) {
                        if(d.res == 100) {
                            location.href='';
                        }else {
                            alert(d.msg);
                        }
                    }
                })
            }
            // 添加最外层一级节点
            var addParent = function() {
                var title = $("#parent_title").val();
                if(!title) {
                    alert("标题不能为空");
                    return;
                }

                _add('',title);
            }
            // 添加子节点
            var addChild = function(pid) {
                var title = $("#child_title").val();
                if(!title) {
                    alert("标题不能为空");
                    return;
                }
                _add(pid,title);
            }


            // 点击触发下拉工具条
            var dropDown = function(id) {
                $("[id^=dropdown_]").slideUp('fast');
                $("#dropdown_"+id).slideToggle();
            }
            return {
                addParent:addParent,
                addChild:addChild,
                dropDown:dropDown
            }

        }())
    </script>
@stop