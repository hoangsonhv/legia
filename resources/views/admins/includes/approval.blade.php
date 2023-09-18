<div id="approval">
  @if (\App\Helpers\PermissionHelper::hasPermission('admin.base-admin.approval'))
    @if($type === 'request')
      @if(\App\Enums\ProcessStatus::Pending == $status)
        @php
          if(!empty($isNotCo)) {
            $pendingApprove = \App\Enums\ProcessStatus::Approved;
          } else {
            $pendingApprove = \App\Enums\ProcessStatus::PendingSurveyPrice;
          }
        @endphp
        <p class="approve">
          <a href="{{ route('admin.base-admin.approval', ['id' => $id, 'type' => $type, 'status' => $pendingApprove]) }}" class="btn btn-success btn-block" onclick="return confirm('Bạn có chắc chắn muốn duyệt thông tin này ?')">DUYỆT</a>
        </p>
        <p class="un-approve">
          <a href="{{ route('admin.base-admin.approval', ['id' => $id, 'type' => $type, 'status' => \App\Enums\ProcessStatus::Unapproved]) }}" class="btn btn-default btn-block" onclick="return confirm('Bạn có chắc chắn muốn không duyệt thông tin này ?')">KHÔNG DUYỆT</a>
        </p>
      @elseif(\App\Enums\ProcessStatus::PendingSurveyPrice == $status)
        <p class="approve">
          <a href="{{ route('admin.base-admin.approval', ['id' => $id, 'type' => $type, 'status' => \App\Enums\ProcessStatus::Approved]) }}" class="btn btn-success btn-block" onclick="return confirm('Bạn có chắc chắn muốn duyệt thông tin này ?')">DUYỆT KHẢO SÁT GIÁ</a>
        </p>
        <p class="un-approve">
          <a href="{{ route('admin.base-admin.approval', ['id' => $id, 'type' => $type, 'status' => \App\Enums\ProcessStatus::Unapproved]) }}" class="btn btn-default btn-block" onclick="return confirm('Bạn có chắc chắn muốn không duyệt thông tin này ?')">KHÔNG DUYỆT KHẢO SÁT GIÁ</a>
        </p>
      @else
        <p class="pending-approve" style="color: #FFF;">
          {{ \App\Enums\ProcessStatus::all()[$status] }}
        </p>
      @endif
    @elseif($type === 'co' && !empty($check_warehouse) && $check_warehouse)
        @permission('admin.base-admin.check-warehouse')
            <p class="approve">
                <a href="{{ route('admin.base-admin.check-warehouse', ['id' => $id, 'value' => \App\Models\Co::ENOUGH_MATERIAL]) }}"
                   class="btn btn-success btn-block"
                   onclick="return confirm('Bạn có chắc chắn xác nhận đủ nguyên vật liệu ?')"
                >
                    ĐỦ NGUYÊN VẬT LIỆU
                </a>
            </p>
            <p class="un-approve">
                <a href="{{ route('admin.base-admin.check-warehouse', ['id' => $id, 'value' => \App\Models\Co::LACK_MATERIAL]) }}"
                   class="btn btn-default btn-block"
                   onclick="return confirm('Bạn có chắc chắn xác nhận không đủ nguyên vật liệu ?')"
                >
                    KHÔNG ĐỦ NGUYÊN VẬT LIỆU
                </a>
            </p>
        @endpermission
    @elseif(\App\Enums\ProcessStatus::Pending == $status)
            <p class="approve">
        <a href="{{ route('admin.base-admin.approval', ['id' => $id, 'type' => $type, 'status' => \App\Enums\ProcessStatus::Approved]) }}" class="btn btn-success btn-block" onclick="return confirm('Bạn có chắc chắn muốn duyệt thông tin này ?')">DUYỆT</a>
      </p>
      <p class="un-approve">
        @if(in_array($type, ['co', 'co-tmp']))
        {!! Form::open(array('route' => 'admin.base-admin.approval', 'method' => 'get', 'enctype' => 'multipart/form-data')) !!}
          {{ Form::hidden('id', $id)}}
          {{ Form::hidden('type', $type)}}
          {{ Form::hidden('status', \App\Enums\ProcessStatus::Unapproved)}}
          <button type='submit' class='btn btn-default btn-block' onclick="return confirm('Bạn có chắc chắn muốn không duyệt thông tin này ?')">
            KHÔNG DUYỆT
          </button>
          <div class="form-group mt-1">
            {{Form::textarea('note', null, array('rows' => 2, 'class' => 'form-control', 'placeholder' => 'Lý do'))}}
          </div>
        {!! Form::close() !!}
        @else
        <a href="{{ route('admin.base-admin.approval', 
            ['id' => $id, 'type' => $type, 'status' => \App\Enums\ProcessStatus::Unapproved]) }}" 
            class="btn btn-default btn-block" 
            onclick="return confirm('Bạn có chắc chắn muốn không duyệt thông tin này ?')">
            KHÔNG DUYỆT
        </a>
        @endif
      </p>
    @else
            <p class="pending-approve" style="color: #FFF;">
        {{ \App\Enums\ProcessStatus::all()[$status] }}
      </p>
    @endif
  @else
    <p style="color: #FFF;">
      {{ \App\Enums\ProcessStatus::all()[$status] }}
    </p>
  @endif
</div>
