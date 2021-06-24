@extends('layout')

@section('content')

<?=$tabs?>

<div class="col-md-12" >

    @include('session_messages')

    <?=$animal_grid?>

</div>

<style>
    
.animal-grid{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-bottom: -1em;
 }
 .animal-grid__block {
    width: calc(100% / 6 - 1em);
    flex-grow: 0;
    flex-shrink: 0;
    margin-right: 1em;
    margin-bottom: 1em;
    display: block;
    background-color: #fff;
    box-shadow: 1px 1px 1px rgba(0,0,0,0.2);
    position: relative;
 }
 .animal-grid__image-outer {
    position: relative;
 }
 .animal-grid__image{
    max-width: 100%;
 }
 .animal-grid__text{
    max-width: 100%;
    padding: 1em 1.25em;
 }
 .animal-grid__animal-name{
    font-weight: 700;
    color: #322525;
    font-size: 1.25em;
    line-height: .8;
    bottom: 0;
    left: 0;
    display: block;
    background-color: rgba(255, 255, 255, 0.3);
    text-transform: uppercase;
    width: 100%;
 }
 .animal-grid__block:hover .animal-grid__animal-name{
    background-color: rgba(255, 255, 255, 0.75);
 }
 .animal-grid__animal-description{
    display: block;
    color: #777;
    font-size: .75em;
    font-style: italic;
    line-height:1;
    margin-top: .5em;
 }
 .animal-grid__block-footer{
    padding: 0 .25em;
 }
 
 .animal-grid__prompts {
    color: #555;
    font-size: .75em;
    line-height:1;
    margin-left: 1.5em;
    padding: 0;
 }
 .animal-grid__prompt-item {
 
 }
 .animal-grid__icons {
    position: absolute;
    margin: 0;
    padding: 0;
    top: .5em;
    right: .5em;
    list-style-type: none;
    text-align: center;
    font-size: 1.2em;
    color: #ce1d1d;
 
 }
 
</style>
@stop
