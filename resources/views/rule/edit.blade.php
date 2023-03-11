@extends('shopify-app::layouts.default')

<div class="loader" style="display: none;">
    <div></div>
    <div></div>
    <div></div>
  </div>
<section></section>

<div id="success_message" style="display:none"></div>
<section class="full-width">
    <article>
        <div class = "card">
    {!! Form::open(['id'=>'update-form']) !!}
    <div class="columns has-sections">
        <ul class="tabs">
            <li class="active"><a> Edit Rule</a></li>
          </ul>
         <div class="card-section">
            {!! form::hidden('InsertRuule', !empty($ruule['id']) ? $ruule['id'] :null, ['id'=>'ruule_id']) !!}

            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('enable_rule', 'Enable Rule', ['class' => 'awesome']); !!}
                      </div>
                      <div class="columns nine">
                        @if(!empty($ruule['rule_status']) && $ruule['rule_status'])
                            {!! Form::checkbox('enable_rule', '1', true, ['id' => 'enable_id']); !!}
                        @else
                            {!! Form::checkbox('enable_rule', '0', false, ['id' => 'enable_id']); !!}
                        @endif
                       </div>
                    </article>
            </div>

            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('title', 'Rule Title', ['class' => 'awesome']); !!}
                        </div>
                       <div class="columns nine">
                        {!! Form::text('rule_title', $ruule['rule_title'], ['class' => 'form-control title', 'required'=>'required']); !!}
                    </div>
                </article>
            </div>

            <div class="row">
                <article>
                    <div class="columns three">
                        {!! Form::label('category', 'Apply Rule To', ['class' => 'awesome']); !!}
                    </div>
                    <div class="columns nine">
                        @if($ruule['rule_category'] == 'Products')
                        {!! Form::select('categories', ['Products' => 'Products', 'All' => 'All'], ['class' => 'categories']); !!}
                        @else
                        {!! Form::select('categories', ['All' => 'All','Products' => 'Products'], ['class' => 'categories']); !!}
                        @endif
                    </div>
                </article>
            </div>
            @if(isset($dataArray))
                <article class="hide-show-section">
                    <div class="columns three">
                        {!! Form::label('product', 'Product(s)', ['class' => 'awesome']); !!}
                    </div>
                    <div class="columns nine">
                        {!! Form::select('products[]', $dataArray, array_keys($dataArray) , ['id' => 'product','class' => 'myselect add_product form-control', 'multiple' => 'multiple']) !!}
                    </div>
                </article>
                @else    
                    <article class="hide-show-section">
                        <div class="columns three">
                        {!! Form::label('product', 'Product(s)', ['class' => 'awesome']); !!}
                        </div>
                        <div class="columns nine">
                        {!! Form::select('products[]', ['pr' => 'products', 'c' => 'collections'], null, ['id' => 'product','class' => 'myselect add_product form-control', 'multiple' => 'multiple']) !!}
                        </div>
                    </article>
                @endif
                <a href="/"><button type="button" class="cancel_course secondary">Cancel</button></a>
                {!! Form::submit('Update', ['class' => 'update_rule']) !!}

        </div>
    </div>
    {{Form::close()}}
        </div>
    </article>
</section>

