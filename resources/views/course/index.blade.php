@extends('shopify-app::layouts.default')

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/course.css') }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Document</title>
</head>
<section></section>

<section class="full-width">
    <article>
        <button class="insert_course">Add Course</button>
        <a href="add-rule"><button class="add_rule">Add Rule</button></a>
    </article>
</section>

<div id="success_message" style="display:none"></div>
<section class="full-width">
    <article>
        <div class="card">
            <div class="search">
                <input type="search" placeholder="Search" id="search">
            </div>
            <body id="course">
                <table id="new_record" class="results">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>        
                    </tbody>
                </table>
                <div id="pagination">
                </div>
        </div>
    </article>
</section>

<!-- Delete Model -->
<div id="DeleteModal" class="modalDialog">
    <div>
        <a href="#close" title="Close" class="close">X</a>
        <h5>Delete Course</h5>
        <h4>Confirm Delete ?</h4>
        <input type="hidden" id="deleteing_id">
        <button class="delete_course">Delete</button>
        <a href="#cancel" title="Close" class="cancel"><button class="secondary">Cancel</button></a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('js/course.js') }}"></script>

</html>