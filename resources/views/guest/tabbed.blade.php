@extends('layout')

@section('content')

<?=$tabs?>

<div class="col-md-12" >

    @include('session_messages')

    <?=$guest_grid?>

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
    width: calc(100% / 4 - 1em);
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
    display: flex;
 }
 .animal-grid__image-weetikveelhoedatinhetengelsheetpotvolkoffie {
    width: 150px;
    height:150px;

 }
 /*
 WORDT OOK DEELS GEBRUIKT OP SINGLE PAGINA's IN ZIJMENU. MET CASCADE FIKSEN
 */
 .animal-grid__icons {
   margin: 0;
   padding: 0;
   width: calc(100% - 150px);
   list-style-type: none;
   text-align: center;
   font-size: 1.2em;
   color: #ce1d1d;
   display: flex;
   flex-direction: column;
   justify-content: flex-start;
 }
 .animal-grid__icon-item {
    flex-basis: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
 }
 /* de user update */
 .animal-grid__icon-item--female {
    background-color: #a618a4;
    color: #f5f5f5; 
 }
 /* hulpverlening update*/
 .animal-grid__icon-item--users {
    background-color: #ce1d1d;
    color: #f5f5f5; 
 }
 /* nog geen updates*/
 .animal-grid__icon-item--heart {
    background-color: #f5f5f5;
    color: #ce1d1d; 
 }
 /* jaarevaluatie update nodig*/
 .animal-grid__icon-item--sign-out {
    background-color: #353535;
    color: #f5f5f5; 
 }
 /* alle updates vereist zijn op tijd */
 .animal-grid__icon-item--all-good {
   background-color: #gold;
   color: #f5f5f5;
 }
 .animal-grid__icon-item--all-good > .fa {
    font-size: 2.5em;
 }

 .animal-grid__icon-item:hover {
    cursor:help
 } 
.animal-grid__icon-item .fa {
   display: inline-block;
   min-width: 18px;
   text-align: center;
}

 .animal-grid__image{
    width: 150px;
    height: 150px;
    object-fit: cover;
    object-position: center;
 }
 .animal-grid__image[src*='placeholder'] {
    filter: brightness(70%);
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
    text-transform: uppercase;
    width: 100%;
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

</style>
@stop
