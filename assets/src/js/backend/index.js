
( function ( $ ) {
	/**
	 * Class Loadmore.
	 */
	class FUTUREWORDPRESS_PROJECT_BACKEND_MAIN {
		/**
		 * Contructor.
		 */
		constructor() {
			this.init();
		}

		init() {
    }
    cv_add() {
      const thisClass = this;
      if( ! window.fwp_form_CV_ADD) {return;}
      var form = window.fwp_form_CV_ADD,
          name = form.name.value,
          file = form.file;
      file.addEventListener( 'change', function( e ) {
        var formData = new FormData();
        if( form.file.dataset.id ) {
          formData.append( 'edit-cv', form.file.dataset.id );
        }
        formData.append( 'action', 'fwp-candidate-add-cv-action' );
        formData.append( 'name', form.name.value );
        formData.append( 'file', $( form.file )[0].files[0] );
        formData.append( '_nonce', thisClass.ajaxNonce );
        $.ajax( {
          url: thisClass.ajaxUrl,
          type: 'POST',
          data: formData,
          processData: false,  // tell jQuery not to process the data
          contentType: false,  // tell jQuery not to set contentType
          dataType: "json",
          success: function( data ) {
            if( data.success ) {
              location.reload();
            } else {
              console.log( data );
            }
          }
        } );
      } );
    }
    cv_edit() {
      document.querySelectorAll( '.edit-cv-fwp' ).forEach( function( e, i ) {
        e.addEventListener( 'click', function( event ) {
            var id = ( this.dataset.id ) ? this.dataset.id : false;
            var name = ( this.dataset.name ) ? this.dataset.name : '';
            if( id ) {
              window.fwp_form_CV_ADD.file.dataset.id = id;
              window.fwp_form_CV_ADD.name.value = name;
              window.fwp_form_CV_ADD.file.click();
            }
        } );
      } );
    }
    cv_delete() {
      const thisClass = this;
      document.querySelectorAll( '.delete-cv-fwp' ).forEach( function( e, i ) {
        e.addEventListener( 'click', function( event ) {
            var id = ( this.dataset.id ) ? this.dataset.id : false;
            var name = ( this.dataset.name ) ? this.dataset.name : '';
            if( id && confirm( thisClass.confirmDeleteCV ) ) {
              var data = { action: 'fwp-candidate-delete-cv-action', id: id, _nonce: thisClass.ajaxNonce };
              if( this.dataset.isCompany ) {
                data.isCompany = this.dataset.isCompany;
                data.action = 'fwp-company-delete-application-action';
              }
              console.log( data );
              $.ajax( {
                url: thisClass.ajaxUrl,
                type: 'POST',
                data: data,
                // processData: false,
                // contentType: false,
                dataType: "json",
                success: function( data ) {
                  if( data.success ) {
                    location.reload();
                  } else {
                    console.log( data );
                  }
                }
              } );
            }
        } );
      } );
    }
		
	}

	new FUTUREWORDPRESS_PROJECT_BACKEND_MAIN();
} )( jQuery );
