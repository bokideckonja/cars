
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('select2');


// Definisi varijable
var vehiclesTable = $('#vehiclesTable');
var vehiclesTbody = vehiclesTable.children('tbody').eq(0);
var category = $('#category');
var pagination = $('.pagination').eq(0);
var currentPage = 1;

category.select2();

// Na promjenu kategorije, fetch-uj vozila
$('#search').on('change', '#category', function (e) {
    getVehicles(1);
});
// Na klik na paginaciju, fetch-uj vozila
$('#search').on('click', '.pagination li a', function(e) {
    e.preventDefault();
    let [host, query] = $(this).attr('href').split('?');
    let queryPieces = query.split('&');
    let page = 1;
    // Loopuj kroz query parove, nadji page={num}
    for(i=0;i<queryPieces.length;i++){
        let [key, value ] = queryPieces[i].split('=');
        if(key == 'page'){
            page = value;
            break;
        }
    }
    getVehicles(page);
});

// Fetch-uj vozila
function getVehicles(page = false) {
    $.ajax({
        url : buildQuery(page),
        dataType: 'json',
    }).done(function (data) {
        currentPage = data.current_page;
        buildTable(data.data);
        buildPagination(data);
        
    }).fail(function () {
        alert('Vehicles could not be loaded.');
    });
}

// Napravi tabelu
function buildTable(data){
    vehiclesTbody.empty();

    for(i=0; i<data.length; i++){
        var row = $('<tr>')
            .append( 
                $('<td>').append( 
                    $('<img>').attr('src',data[i].image).addClass('img-responsive') 
                ).css('width','200px')
            ).append(
                $('<td>').append( 
                    $('<h4>').html('<strong>'+data[i].name+'</strong>') 
                ).append(
                    $('<p>')
                        .html('Year: <strong>'+data[i].year+'</strong><br/>Mileage: <strong>'+data[i].miles+' km</strong><br/>Brand: <strong>'+data[i].category.name+'</strong>')
                )
            ).append(
                $('<td>').append( 
                    $('<strong>').text(data[i].price+' €')
                )
            );

        vehiclesTbody.append(row);
    }
    console.log("table built");
}

// Napravi paginacijske linkove
function buildPagination(data){
    pagination.empty();

    // Ako ima samo jedna strana, sakrij paginaciju
    if(data.last_page == 1){
        return;
    }

    // Generisi prethodni link
    var prev = $('<li>');
    if(data.current_page == 1){
        prev.append($('<span>').text('«')).addClass('disabled');
    }else{
        prev.append($('<a>').attr('href', buildQuery(currentPage-1)).text('«'));
    }
    pagination.append(prev);

    // Generisi brojeve
    for(i=1;i<=data.last_page;i++){
        var li = $('<li>');
        
        if(i == currentPage){
            li.append($('<span>').text(i)).addClass('active');
        }else{
            li.append($('<a>').attr('href', buildQuery(i) ).text(i));
        }
        pagination.append(li);
    }

    // Generisi next link
    var next = $('<li>');
    if(data.current_page == data.last_page){
        next.append($('<span>').text('»')).addClass('disabled');
    }else{
        next.append($('<a>').attr('href', buildQuery(currentPage+1) ).text('»'));
    }
    pagination.append(next);
}

// Napravi query
function buildQuery(page){
    var query = [];
    var cat = category.val();
    // Dodaj category_id, samo ako je izabran
    if(cat != 0){
        query.push('category_id='+cat);
    }

    // Ako nije zadata strana, postavi trenutnu
    if(page !== false){
        query.push('page='+page);
    }else{
        query.push('page='+currentPage);
    }
    
    // Izgradi link
    if(query.length > 0){
        q = Laravel.url+'?'+query.join('&');
    }else{
        q = '';
    }

    return q;
}

