{{-- <div class="update_rule_form" style="display:none"> --}}
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
                <a class="button primary"  href="{{ custom_route('general_setting_form',['shop='.Auth::user()->name]) }}">Setting</a>
                <a class="button secondary"  href="{{ custom_route('home') }}">Rules</a>
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
                    <dd class="sm-content">Rule Updated successfully</dd>
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

                <article class="row" style="margin-top:10px">
                    <div class="columns three align-left">
                        <h5>Update Rule</h5>
                    </div>
                    <div class="columnns nine">
                    </div>
                </article>


        {!! Form::open(['id' => 'update-rule']) !!}
        <div class="row">
            <article>
                <div class="columns three">
                    {!! Form::label('enable_rule', 'Enable Rule', ['class' => 'awesome']) !!}
                </div>
                <div class="columns nine">
                    @if(!empty($rule['enable_rule']))
                    {!! Form::checkbox('enable_rule', '1', true, ['id' => 'enable-rule']) !!}
                    @else
                    {!! Form::checkbox('enable_rule', '0', false, ['id' => 'enable-rule']) !!}
                    @endif
                </div>
            </article>
        </div>

        <div class="row">
            <article>
                <div class="columns three">
                    {!! Form::label('rule_title', 'Rule Title', ['class' => 'awesome']) !!}
                </div>
                <div class="columns nine">
                    @if(!empty($rule['rule_title']))
                    {!! Form::text('rule_title', $rule['rule_title'], ['id' => 'rule-title']) !!}
                    @else
                    {!! Form::text('rule_title', '', ['id' => 'rule-title']) !!}
                    @endif
                    <span class="text-danger error-text rule_title_error"></span>
                </div>
            </article>
        </div>

        <div class="row">
            <article>
                <div class="columns three align-left">
                    {!! Form::label('rule_applied_to', 'Rule Apply To', ['class' => 'awesome']) !!}
                </div>
                <div class="columns nine alignment">
                    @if(!empty($rule['rule_applied_to']))
                    {{-- {{dd ()}} --}}
                    {!! Form::select('rule_applied_to', ['all'=>'All','product' => 'Product', 'collection' =>'Collection'], $rule['rule_applied_to'], [
                        'id' => 'rule-apply-to',
                        'class' => '',
                        ]) !!}
                        @else
                        {!! Form::select('rule_applied_to', ['all'=>'All','product' => 'Product', 'collection' =>'Collection'], 'all', [
                            'id' => 'rule-apply-to',
                            'class' => '',
                            ]) !!}
                            @endif
                </div>
            </article>
        </div>
        {{-- @if(($rule['rule_applied_to']!= 'all')) --}}
        <div class="row product-hide-show-section" style="display:none;">
            <article>
                <div class="columns three align-left">
                    {!! Form::label('Product', 'Product', ['class' => 'awesome','id'=>'product-collection-label']) !!}
                </div>
                {{-- {{dd($productTitle)}} --}}
                <div class="columns nine alignment">
                    {!! Form::select('product_collection_value[]',$title ?? '', array_keys($title) ?? [], [
                        'id' => 'product-collection-value',
                        'class' => 'form-control product',
                        'multiple' => 'multiple', 

                    ]) !!}
                    <span class="text-danger error-text product_collection_value_error"></span>
                </div>
            </article>
        </div>
        {{-- @endif --}}

        <div class="row">
            <article>
                <div class="columns three">
                </div>
                <div class="columns nine align-right">
                    <input type='hidden' name='checkRuleId' value= {{$rule['id']}} id='check-rule-id' />
                    {{-- {!! Form::hidden('rule_id', $rule['id'], ['id' => 'rule_id']) !!} --}}
                    <a href="{{ custom_route('home') }}" title="Close" class="button secondary login-cancel-btn">Cancel</a>
                    {!! Form::submit('Update', ['class' => 'update_rule']) !!}
                    {!! Form::close() !!} 
                </div>
            </article>
        </div>


             </div>
            </div>
        </div>
    </article>
</section>
       

    
@endsection
{{-- </div> --}}