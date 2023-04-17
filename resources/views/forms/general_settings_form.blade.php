@extends('shopify-app::layouts.default')
@section('content')
<section></section>
<section class="full-width">
    <article>
        <div class="columns ten">
            <a class="button secondary" href=""><i class="icon-question append-spacing"></i><span>User Guide</span></a>
            <a class="button secondary" href="">
                <i class="icon-addition append-spacing"></i>
                <span>Change Plan</span>
            </a>
            <a class="link" target="_blank" style="padding-right: 10px;" href="https://support.extendons.com/" >Get Support</a>
        </div>
        <div class=" columns two">
            <div class="align-right" style="">
                <a class="button secondary" href="{{custom_route('home')}}">
                    <span>Back</span>
                </a>
                <a class="button backloader primary"  href="{{ custom_route('add_rule_form') }}">Add Rule</a>
            </div>
        </div>
    </article>
</section>



<section class="full-width success-message default-hidden">
    <article>
        <div class="columns twelve">
            <div class="alert success">
                <dl>
                    <dt>Success Alert</dt>
                    <dd class="sm-content">Rule added successfully</dd>
                </dl>
            </div>
        </div>
    </article>
</section>


<section class="full-width"> 
   <article>
       <div class="column twelve">
           <div class="columns has-sections card">
            <div class="card-section">
            {!! Form::open(['id' => 'add-setting']) !!}
            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('enable_app', 'Enable App', ['class' => 'awesome']) !!}
                    </div>
                    <div class="columns nine">
                        @if(!empty($setting['enable_app']))
                        {!! Form::checkbox('enable_app', '1', true, ['id' => 'enable_id']) !!}
                        @else
                        {!! Form::checkbox('enable_app', '0', false, ['id' => 'enable_id']) !!}
                        @endif
                    </div>
                </article>
            </div>

            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('customize_change_btn', 'Customize Change Button', ['class' => 'awesome']) !!}
                    </div>
                    <div class="columns nine">
                        @if(!empty($setting['customize_change_btn']))
                        {!! Form::checkbox('customize_change_btn', '', true, ['id' => 'customize_change_btn']) !!}
                        @else
                        {!! Form::checkbox('customize_change_btn', '', false, ['id' => 'customize_change_btn']) !!}
                        @endif
                    </div>
                </article>
            </div>

            <article class="hide-show-section">
                <div class="card">
                    <div class="row">
                        <div class="columns three">
                            {!! Form::label('text_color', 'Text Color', ['class' => 'awesome']) !!}
                        </div>
                        <div class="columns nine">
                            @if(!empty($setting['text_color']))
                            {!! Form::Color('text_color', $setting['text_color'],['style'=>'width:100%'], ['id' => 'text_color']) !!}
                            @else
                            {!! Form::Color('text_color', '#000000',['style'=>'width:100%'], ['id' => 'text_color']) !!}
                            @endif
                            <span class="text-danger error-text text_color_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="columns three">
                            {!! Form::label('text_background_color', 'Text Background Color', ['class' => 'awesome']) !!}
                        </div>
                        <div class="columns nine">
                            @if(!empty($setting['text_background_color']))
                            {!! Form::Color('text_background_color', $setting['text_background_color'],['style'=>'width:100%'], ['id' => 'text_background_color']) !!}
                            @else
                            {!! Form::Color('text_background_color', '#FFFFFF',['style'=>'width:100%'], ['id' => 'text_background_color']) !!}
                            @endif
                            <span class="text-danger error-text text_background_color_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="columns three">
                            {!! Form::label('change_btn_text', 'Change Text of "Change" Button', ['class' => 'awesome']) !!}
                        </div>
                        <div class="columns nine">
                            @if(!empty($setting['change_btn_text']))
                            {!! Form::text('change_btn_text', $setting['change_btn_text'], ['id' => 'change_btn_text']) !!}
                            @else
                            {!! Form::text('change_btn_text', 'Change', ['id' => 'change_btn_text']) !!}
                            @endif
                            <span class="text-danger error-text change_btn_text_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="columns three">
                            {!! Form::label('update_btn_text', 'Change Text of "Update" Button', ['class' => 'awesome']) !!}
                        </div>
                        <div class="columns nine">
                            @if(!empty($setting['update_btn_text']))
                            {!! Form::text('update_btn_text', $setting['update_btn_text'], ['id' => 'update_btn_text']) !!}
                            @else
                            {!! Form::text('update_btn_text', 'Update', ['id' => 'update_btn_text']) !!}
                            @endif
                            <span class="text-danger error-text update_btn_text_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="columns three">
                            {!! Form::label('cancel_btn_text', 'Change Text of "Cancel" Button', ['class' => 'awesome']) !!}
                        </div>
                        <div class="columns nine">
                            @if(!empty($setting['cancel_btn_text']))
                            {!! Form::text('cancel_btn_text', $setting['cancel_btn_text'], ['id' => 'cancel_btn_text']) !!}
                            @else
                            {!! Form::text('cancel_btn_text', 'Cancel', ['id' => 'cancel_btn_text']) !!}
                            @endif
                            <span class="text-danger error-text cancel_btn_text_error"></span>
                        </div>
                    </div>

                </div>
            </article>

            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('merchant_msg', 'Enable Merchant Message', ['class' => 'awesome']) !!}
                    </div>
                    <div class="columns nine">
                        @if(!empty($setting['enable_merchant_msg']))
                        {!! Form::checkbox('enable_merchant_msg', '', true, ['id' => 'enable_merchant_msg']) !!}
                        @else
                        {!! Form::checkbox('enable_merchant_msg', '', false, ['id' => 'enable_merchant_msg']) !!}
                        @endif
                    </div>
                </article>
            </div>

            <article class="merchant-msg-hide-show-section">
                <div class="card">
                    <div class="row">
                        <div class="columns three">
                            {!! Form::label('merchant_msg_text', 'Merchant to customer message', ['class' => 'awesome']) !!}
                        </div>
                        <div class="columns nine">
                            @if(!empty($setting['merchant_msg']))
                            {!! Form::textarea('merchant_msg', $setting['merchant_msg'], ['id' => 'merchant_msg', 'rows' => 4]) !!}
                            @else
                            {!! Form::textarea('merchant_msg', '', ['id' => 'merchant_msg', 'rows' => 4]) !!}
                            @endif
                            <span class="text-danger error-text merchant_msg_error"></span>
                        </div>
                    </div>
                </div>
            </article>

            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('customer_msg', 'Enable Customer Message', ['class' => 'awesome']) !!}
                    </div>
                    <div class="columns nine">
                        @if(!empty($setting['enable_customer_msg']))
                        {!! Form::checkbox('enable_customer_msg', '', true, ['id' => 'enable_customer_msg']) !!}
                        @else
                        {!! Form::checkbox('enable_customer_msg', '', false, ['id' => 'enable_customer_msg']) !!}
                        @endif
                    </div>
                </article>
            </div>

            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('survey_question', 'Enable Survey Question', ['class' => 'awesome']) !!}
                    </div>
                    <div class="columns nine">
                        @if(!empty($setting['enable_survey_question']))
                        {!! Form::checkbox('enable_survey_question', '', true, ['id' => 'enable_survey_question']) !!}
                        @else
                        {!! Form::checkbox('enable_survey_question', '', false, ['id' => 'enable_survey_question']) !!}
                        @endif
                    </div>
                </article>
            </div>

            <div class="row">
                <article>
                    <div class="columns three">
                    </div>
                    <div class="columns nine align-right">
                        {!! Form::submit('Save', ['class' => 'save_rule']) !!}
                    </div>
                </article>
            </div>
            {{-- {!! form::hidden('SaveRule', 'id', ['id'=>'save_rule']) !!} --}}
            {{ Form::close() }}
           </div>
         </div>
       </div>
   </article>
</section>
@endsection
            
            
      
