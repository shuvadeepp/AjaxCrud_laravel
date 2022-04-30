<!DOCTYPE html>
<html lang="en">
   <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <title> Laravel </title>
   </head>
   <body>
      <!-- Button trigger modal -->
      <div class="row justify-content-center">  
         <button type="button" class="btn btn-primary btn-lg" id="addKaro">
         <b> ADD USER DATA </b> 
         </button>
      </div>
      <!-- create table -->
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-10">
               <table class="table table bordered">
                  <thead>
                     <tr>
                        <th>Sl#</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody id="List_user">
                     <?php //echo"<pre>";print_r($showDatas); ?>
                     @foreach( $showDatas as $show )
                     <tr id="List_user_id{{ $show-> id }}">
                        <td> {{ $show-> id }} </td>
                        <td> {{ $show-> name }} </td>
                        <td> {{ $show-> address }} </td>
                        <td> {{ $show-> phone }} </td>
                        <td>
                           <button type="button" id="edit_Karo" data-id="{{ $show-> id }}" class="btn btn-sm btn-success"> EDIT </button>
                           <button type="button" id="delete_Karo" data-id="{{ $show-> id }}" class="btn btn-sm btn-danger"> DELETE </button>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- create table -->
      <!-- Modal -->
      <div class="modal fade" id="modal_open" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <form id="formUser">
                  <div class="modal-header">
                     <h5 class="modal-title" id="ModalTitle"> Modal title </h5>
                  </div>

                  <div class="modal-body">
                     {{ csrf_field() }}
                    <input  type="hidden" name="id" id="hidden_id" value = '0' >
                    <input type="text" name="name" id="name_user" class="form-control" placeholder="Enter Name"><br>
                    <input type="text" name="address" id="address_user" class="form-control" placeholder="Enter Address"><br>
                    <input type="text" name="phone" id="phone_user" class="form-control" maxlength="10" placeholder="Enter Phone">
                  </div>

                  <div class="modal-footer">
                     <button type="submit" class="btn btn-success">Submit</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script>

        $(document).ready(function(){
            $.ajaxSetup({
                header:{
                    'x-csrf-token' : $('meta[name="csrf-token"]').attr('content')
                }
            });
        });


         $('#addKaro').on('click', function (){
            $('#formUser').trigger('reset');
            $('#hidden_id').val('reset');
            $('#ModalTitle').html('ADD USER');
            $("#modal_open").modal('show');
         });
         //edit:
         $("body").on('click','#edit_Karo', function(){
            var id = $(this).data('id');
            //ajax
            $.get('user/' + id +'/edit', function(res){
               $('#ModalTitle').html('EDIT USER');
               $('#hidden_id').val(res.id);
               $('#name_user').val(res.name);
               $('#address_user').val(res.address);
               $('#phone_user').val(res.phone);
               $("#modal_open").modal('show');
            }) 
         })

         //delete:
         $("body").on('click','#delete_Karo', function(){
            var id = $(this).data('id');
            confirm(' Are you sure want to delete ! ');
            // alert(id); return false;
            //ajax
            $.ajax({
               type: "DELETE",
               url : "user/destroy/" + id,
               headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(res){
               $("List_user" + id).remove();
            })
         })

         //save data
         $("form").on('submit', function(e){
               e.preventDefault();
                  $.ajax({
                     url : "user/store",
                     data : $("#formUser").serialize(),
                     type : "POST"
                  }).done(function(res){     
                     var row = '<tr id="List_user_id' + res.id + '" >';
                           row = '<td>' + res.id      + '</td>';
                           row = '<td>' + res.name    + '</td>';
                           row = '<td>' + res.address + '</td>';
                           row = '<td>' + res.phone   + '</td>';

                           row = '<td>' + '<button type="button" id="edit_Karo" data-id="' + res.id + '" class="btn btn-info"> EDIT </button>' + 
                           '<button type="button" id="delete_Karo" data-id="' + res.id + '" class="btn btn-danger"> DELETE </button>' + '</td>';

                           if( $("#hidden_id").val() ) {
                              $("#List_user" + res.id).replaceWith(row);
                           }else{
                              $("#List_user").prepend(row);
                           }

                           $("#formUser").trigger('reset');
                           $("#modal_open").modal("show");
                  })
         })
      </script>
   </body>
</html>