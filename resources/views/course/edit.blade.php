@extends('shopify-app::layouts.default')
    <table>
        {!! Form::open(['id'=>'update-form']) !!}
        <div class="columns has-sections">
            <ul class="tabs">
                <li class="active"><a> Update</a></li>
            </ul>

            <div class="card-section">
                {!! form::hidden('InsertCourse', !empty($course['id']) ? $course['id'] :null, ['id'=>'course_id']) !!}
                <!-- <input type="hidden" value="{{ $course->id }}" id="course_id"> -->

                <div class="row">
                    <article>
                           <div class="columns three">
                                  {!! Form::label('name', 'Name', ['class' => 'awesome']); !!}
                             </div>
                             <div class="columns nine">
                                {!! Form::text('name', $course->name, ['class' => 'form-control name', 'required'=>'required']); !!}
                                <!-- <input type="text" value="{{ $course->name }}" required class="name"> -->
                            </div>
                    </article>
                </div>

                <div class="row">
                    <article>
                        <div class="columns three">
                            {!! Form::label('code', 'Code', ['class' => 'awesome']); !!}
                        </div>
                        <div class="columns nine">
                            {!! Form::text('name', $course->code, ['class' => 'form-control code', 'required'=>'required']); !!}
                        </div>
                    </article>
                </div>

                <article>
                    <div class="columns three">
                        {!! Form::label('description', 'Description', ['class' => 'awesome']); !!}
                    </div>
                    <div class="columns nine">
                        {!! Form::text('description', $course->description, ['class' => 'form-control desc', 'required'=>'required']);!!}
                    </div>
                </article>

                <div class="row">
                    <article>
                        <div class="columns three">
                            {!! Form::label('product', 'Product', ['class' => 'awesome']); !!}
                        </div>
                        <div class="columns nine">
                            {!! Form::select('products[]', $dataArray, array_keys($dataArray) , ['id' => 'product','class' => 'myselect add_product form-control', 'multiple' => 'multiple']) !!}
                        </div>
                    </article>
                </div>
                <button type="button" id="update_course">Update</button>
                <a href="/"><button type="button" class="cancel_course secondary">Cancel</button></a>
            </div>
        </div>
        {{Form::close()}}
    </table>
    <!-- <table>
            <div class="columns has-sections">
                <ul class="tabs">
                    <li class="active"><a> Update</a></li>
                </ul>

                <div class="card-section">
                    <input type="hidden" value="{{ $course->id }}" id="course_id">

                    <div class="row">
                        <article>
                            <div class="columns three">
                                <label for="">Name</label>
                            </div>
                            <div class="columns nine">
                                <input type="text" value="{{ $course->name }}" required class="name">
                            </div>
                        </article>
                    </div>

                    <div class="row">
                        <article>
                            <div class="columns three">
                                <label for="">Code</label>
                            </div>
                            <div class="columns nine">
                                <input type="text" value="{{ $course->code }}" required class="code">
                            </div>
                        </article>
                    </div>
 
                    <article>
                        <div class="columns three">
                            <label for="">Description</label>
                        </div>
                        <div class="columns nine">
                            <input type="text" value="{{ $course->description }}" required class="desc">
                        </div>
                    </article>

                    <button type="button" id="update_course">Update</button>
                    <a href="/"><button type="button" class="cancel_course secondary">Cancel</button></a>
                </div>
            </div>
        </table> -->

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('js/course.js') }}"></script>

</html>