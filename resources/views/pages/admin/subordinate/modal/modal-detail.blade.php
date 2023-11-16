 <!-- Modal -->
 <div class="modal fade" id="detailModal{{ $office->id }}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999">
     <div class="modal-dialog " role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">{{ $office->name }}</h5>
                 <button type="button" class="close" data-dismiss="modal">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 {{-- //URUNG = controller blm dibuat --}}
                 <form>
                     <div class="form-group">
                         <label for="exportType">Select Export Type:</label>
                         <select class="form-control" name="type" id="type">
                             <option value="excel">Excel</option>
                             <option value="pdf">PDF</option>
                         </select>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary">Save changes</button>
             </div>
         </div>
     </div>
 </div>
