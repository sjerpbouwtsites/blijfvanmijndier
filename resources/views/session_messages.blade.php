@if (Session::has('message'))
<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if (Session::has('js_console'))
<script>
let naarConsole = "{{ Session::get('js_console') }}".replace(/&quot;/g,"'");
console.log(naarConsole);</script>
@endif