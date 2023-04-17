@extends('shopify-app::layouts.default')

@section('content')
<section>
</section>

<section class="full-width">
        <article>
            <div class="columns seven">
                <a class="button secondary" href=""><i class="icon-question append-spacing"></i><span>User Guide</span></a>
                <a class="button secondary" href="">
                    <i class="icon-addition append-spacing"></i>
                    <span>Change Plan</span>
                </a>
                <a class="link" target="_blank" style="padding-right: 10px;" href="https://support.extendons.com/" >Get Support</a>
            </div>
            <div class="columns five align-right">
                <a class="button general-setting" href="{{ custom_route('general_setting_form') }}">Setting</a>
                <a class="button add-rules" href="{{custom_route('add_rule_form')}}">Add Rule</a>
                <a class="button analytics" href="">Analytics</a>
            </div>
        </article>
        <br>
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

    @include('listing.rules_listing')
    @include('models.delete_rule_model')
@endsection

