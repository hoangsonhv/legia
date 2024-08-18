    <a class="float-right mb-2" target="_blank" href="{{route('admin.price-survey.edit', ['id' => $surveyPrice->id])}}">
        <span class="btn btn-sm btn-info">Xem chi tiết</span>
    </a>
<div class="card-body w-100" style="max-height: 400px; overflow-y: scroll">
    {{-- <div class="form-group">
        <label for="name">IMPO/DOME<b style="color: red;"> (*)</b></label>
        {!! Form::select('type', \App\Models\PriceSurvey::ARR_TYPE, $surveyPrice->type, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
    </div> --}}
    <div class="form-group">
        <label for="tax_code">Nhà cung cấp<b style="color: red;"> (*)</b></label>
        {!! Form::text('supplier', $surveyPrice->supplier, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
    </div>
    {{-- <div class="form-group">
        <label for="tax_code">Nhóm sản phẩm<b style="color: red;"> (*)</b></label>
        {!! Form::text('product_group', $surveyPrice->product_group, array('class' => 'form-control', 'required' => 'required', 'placeholder' => 'Nhóm sản phẩm', 'readonly' => 'readonly')) !!}
    </div> --}}
    <div class="form-group">
        <label for="address">Người yêu cầu</label>
        {!! Form::text('request_person', $surveyPrice->request_person, array('class' => 'form-control', 'placeholder' => 'Người yêu cầu', 'readonly' => 'readonly')) !!}
    </div>
    <div class="form-group">
        <label for="date_request">Ngày yêu cầu</label>
        <div class="input-group">
            {!! Form::text('date_request', $surveyPrice->date_request, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="deadline">Deadline Cần hàng</label>
        <div class="input-group">
            {!! Form::text('deadline', $surveyPrice->deadline, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="question_date">Ngày hỏi NCC</label>
        <div class="input-group">
            {!! Form::text('question_date', $surveyPrice->question_date, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="result_date">Ngày có kết quả</label>
        <div class="input-group">
            {!! Form::text('result_date', $surveyPrice->result_date, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="name">Duyệt mua<b style="color: red;"> (*)</b></label>
        {!! Form::select('status', \App\Models\PriceSurvey::ARR_STATUS, $surveyPrice->status, array('class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly')) !!}
    </div>
    <div class="form-group">
        <label for="phone">Giá trị báo giá (Bao gồm VAT)<b style="color: red;"> (*)</b></label>
        {!! Form::text('tmp_price', number_format($surveyPrice->price), array('class' => 'form-control','required' => 'required', 'placeholder' => 'Giá trị báo giá', 'readonly' => 'readonly')) !!}
        {!! Form::hidden('price', null) !!}
    </div>
    <div class="form-group">
        <label for="phone">Số ngày quá hạn thanh toán</label>
        {!! Form::number('number_date_wait_pay', $surveyPrice->number_date_wait_pay, array('class' => 'form-control', 'placeholder' => 'Số ngày quá hạn thanh toán', 'readonly' => 'readonly')) !!}
    </div>
    <div class="form-group">
        <label for="phone">Ghi chú</label>
        {!! Form::textarea('note', $surveyPrice->note, array('class' => 'form-control', 'placeholder' => 'Ghi chú', 'rows' => 2, 'readonly' => 'readonly')) !!}
    </div>
</div>
