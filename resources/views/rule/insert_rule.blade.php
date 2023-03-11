@extends('shopify-app::layouts.default')
<div class="loader" style="display: none;">
    <div></div>
    <div></div>
    <div></div>
  </div>
@section('content')
<table>
<section></section>
<div id="success_message" style="display:none"></div>
<section class="full-width">
    <article>
        <div class = "card">
        {!! Form::open() !!}
        <div class="columns has-sections"> 
             <ul class="tabs">
                <li class="active"><a> Add Rule</a></li>
             </ul>
              <div class="card-section">
                <div class="row">
                    <article>
                         <div class="columns three">
                            {!! Form::label('enable_rule', 'Enable Rule', ['class' => 'awesome']); !!}
                         </div>
                         <div class="columns nine">
                           {!! Form::checkbox('enable_rule', $ruule['enable_rule'] , false, ['id' => 'enable_id']); !!} 
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
                            {!! Form::select('categories', ['All' => 'All', 'Products' => 'Products'], ['id' => 'category', 'class' => 'categories']); !!}
                        </div>
                    </article>
                </div>

                <article class="hide-show-section">
                    <div class="columns three">
                        {!! Form::label('product', 'Product(s)', ['class' => 'awesome']); !!}
                     </div>
                     <div class="columns nine">
                        {!! Form::select('products[]', ['Small' => 'S', 'Products' => 'P'], null, ['id' => 'product','class' => 'myselect add_product form-control', 'multiple' => 'multiple']) !!}
                    </div>
                    <!-- {!! Form::select('products[]', ['pr' => 'products', 'c' => 'collections'], null, ['id' => 'product', 'placeholder' => 'Please Select the Product', 'multiple' => 'multiple', 'class' => 'myselect', 'style' => 'width:100%']) !!} -->
                </article>
                {!! form::hidden('SaveRuule', $ruule['id'], ['id'=>'save_ruule_id']) !!}
                {!! Form::submit('Save', ['class' => 'save_rule']) !!}
                <a href="/"><button type="button" class="cancel_course secondary">Cancel</button></a>
             </div>
         </div>
         {{Form::close()}}
        </div>
    </article>
</section>

@endsection