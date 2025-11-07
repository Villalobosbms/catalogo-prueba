let id = null;

function mensaje(color, mensaje) {
  $('#alerta').fadeIn("fast");
  if(color === "green"){
    $('#alerta').css("background-color", "#8bf78bff");
    $('#alerta').css("color", "#083608ff");
    $('#alerta').text(mensaje.toUpperCase());
    setTimeout(function() {
      $('#alerta').fadeOut("fast");
    }, 3000)
  } else {
    $('#alerta').css("background-color", "#e98a8aff");
    $('#alerta').css("color", "#6e0505ff");
    $('#alerta').text(mensaje.toUpperCase());
    setTimeout(function() {
      $('#alerta').fadeOut("fast");
    }, 3000)
  }
}

$(document).ready(function() {
  // abrir modal
  $('#add-producto').on('click', function() {
    $('#miModal').fadeIn(); 
  });

  // cerrar modal al hacer clic en la X
  $('.cerrar').on('click', function() {
    $('#miModal').fadeOut();
    $('#modalActualizar').fadeOut();
  });

  $('#cancelar-formulario-actualizar').on('click', function(){
      $('#modalActualizar').fadeOut("fast", function() {
        $(".input-formulario").val('');
      });
    });

  $('#cancelar-formulario').on('click', function(){
    $('#miModal').fadeOut("fast", function() {
      $(".input-formulario").val('');
    });
  });

  $('#formulario_enviar').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: "http://localhost:8080/catalogo/agregarProducto", 
      type: 'POST',
      dataType: 'json',
      data: $(this).serialize(),
      success: function(res) {
        $('#miModal').fadeOut("fast", function() {
          $(".input-formulario").val('');
        });
        if(res.success = true){
          mensaje("green", "Producto agregado correctamente para verlo de en VER DATOS - ACTUALIZAR");
        }
      },
      error: function(res) {
        mensaje("red", res.responseJSON.error);
      }
    })
  });

  $('.list-producto').click(function(e) {
    e.preventDefault();

    $.ajax({
      url: "http://localhost:8080/catalogo/listarTodos",
      type: 'GET',
      dataType: 'json',
      success: function(res) {
        $('#miTabla tbody').empty();

        let data = res
        console.log(res)
        res.forEach(function(p) {
          $('#miTabla tbody').append(`
            <tr>
              <td id="td-id">${p.id}</td>
              <td id="td-nombre">${p.nombre.toUpperCase()}</td>
              <td id="td-precio">${p.precio}</td>
              <td id="td-cantidad">${p.cantidad}</td>
              <td><button id="eliminar-producto">ELIMINAR</button></td>
              <td><button id="editar-producto">EDITAR</button></td>
            </tr>
          `);
        });
      },
      error: function() {
        console.log("")
      }
    })
  });

  $(document).on('click', '#eliminar-producto', function(e){
    e.preventDefault();
    id = $(this).closest('tr').find('td').eq(0).text();

    $.ajax({
      url: 'http://localhost:8080/catalogo/eliminar',
      type: 'DELETE',
      dataType: 'json',
      data: { id: id },
      success: function(res) {
        $('#miTabla tbody').find('tr').filter(function() {
          return $(this).find('td').eq(0).text() == id;
        }).remove();
        if(res.success = true){
          mensaje("green", "Producto eliminado correctamente");
        }
        id = null;
      },
      error: function(err) {
        mensaje("red", res.responseJSON.error);
         id = null;
    }
    });
  })

  $(document).on('click', '#editar-producto', function(e) {
    e.preventDefault();
    let tr = $(this).closest('tr');
    id = tr.find('td').eq(0).text();
    let nombre = tr.find('td').eq(1).text();
    let precio = tr.find('td').eq(2).text();
    let cantidad = tr.find('td').eq(3).text();

    $("#a-id").val(id);
    $('#modalActualizar').fadeIn("fast");
    $("#a-nombre").val(nombre);
    $("#a-precio").val(precio);
    $("#a-cantidad").val(cantidad);
  });

  $('#formulario_actualizar').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: "http://localhost:8080/catalogo/actualizar", 
      type: 'POST',
      dataType: 'json',
      data: $(this).serialize(),
      success: function(res) {
        $('#modalActualizar').fadeOut("fast", function() {
          $(".formulario_actualizar").val('');
        });

        let fila = $('#miTabla tbody tr').filter(function() {
          return $(this).find('td').eq(0).text() == id;
        });

        fila.find('td').eq(1).text(res.nombre);
        fila.find('td').eq(2).text(res.precio);
        fila.find('td').eq(3).text(res.cantidad);
        if(res.success == true){
          mensaje("green", "Producto actualizado correctamente");
        }
      },
      error: function(res) {
        mensaje("red", res.responseJSON.error);
      }
    });
  });
});
