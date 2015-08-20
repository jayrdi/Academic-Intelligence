@extends('app')

@section('content')

<div class="row">
	<!-- search params -->
	<!-- TEST -->
	<!-- using Illuminate\Html\HtmlServiceProvider package -->
	{!! Form::open(['url' => 'data', 'id' => 'form']) !!}
		<fieldset>
			<div class="form-group">
				<div class="col-lg-6 well bs-component">

					<div class="journal_fields_wrap">
						<!-- keyword(s) for journal name(s) -->
						<h4 class="form_title">Journal</h4>
						<div class="journal_buttons">
							<a class="btn btn-success" target="_blank" href="http://ip-science.thomsonreuters.com/cgi-bin/jrnlst/jloptions.cgi?PC=D"
							    data-toggle="tooltip-down" title="Search Thomson Reuters for journals">Journal List</a>
							    <!-- add extra text field for journals -->
							{!! Form::button('<span class="glyphicon glyphicon-plus"></span>    Add more fields', ['class' => 'add_journal_field_button btn btn-info']) !!}
						</div> <!-- journal_buttons -->
						<div class="form_field">
							<!-- params: textbox(name, default value, options array) -->
							{!! Form::text('journal1', null, ['class' => 'form-control', 'data-toggle' => 'tooltip-right', 'title' => 'Please enter only one journal per box']) !!}
						</div> <!-- form_field -->
					</div> <!-- journal_fields_wrap -->

					<div class="title_fields_wrap">
						<!-- keyword(s) for paper title(s) -->
						<h4 class="form_title">Keyword</h4>
						<div class="title_buttons">
							<!-- add extra text field for keywords -->
							{!! Form::button('<span class="glyphicon glyphicon-plus"></span>    Add more fields', ['class' => 'add_title_field_button btn btn-info']) !!}
						</div> <!-- title_buttons -->
						<div class="form_field">
							<!-- params: textbox(name, default value, options array) -->
							{!! Form::text('title1', null, ['class' => 'form-control', 'data-toggle' => 'tooltip-right', 'title' => 'Please enter only one title per box']) !!}
						</div> <!-- form_field -->
					</div> <!-- title_fields_wrap -->

					<h4 class="form_title">Time Span</h4></br>
					<!-- should inherit font from #form when main form is correctly set up above -->
					{!! Form::label('select', 'From:', ['class' => 'col-lg-2 control-label']) !!}
					<div class="col-lg-3">
					    {!! Form::select('timeStart', [], 'Select', ['class' => 'form-control', 'id' => 'select']) !!}
					</div> <!-- col-lg-3 -->
					{!! Form::label('select', 'To:', ['class' => 'col-lg-2 control-label']) !!}
					<div class="col-lg-3">
					    {!! Form::select('timeEnd', [], 'Select', ['class' => 'form-control', 'id' => 'select']) !!}
					</div> <!-- col-lg-3 -->
					<br/><br/>

					<!-- execute search; submit button -->
					{!! Form::button('<strong>Submit</strong><span class="glyphicon glyphicon-transfer"></span>', ['type' => 'submit', 'class' => 'btn btn-primary btn-lg', 'id' => 'submit']) !!}

				</div> <!-- col-lg-6 -->

				<div class="col-lg-6 well bs-component">

					<div class="modal-dialog">
						<h4>Notes</h4>
						<p>This application is optimised for Chrome.</p>
						<p>In order to get the best results from your search,<br/>enter one or more journals.</p>
						<p>Keywords and time spans are optional but can be<br/>used to refine your search.</p>
					</div> <!-- modal-dialog -->

				</div> <!-- col-lg-6 -->

			</div> <!-- form-group -->
		</fieldset>
	</form>
	{!! Form::close() !!}
</div> <!-- row -->

@stop