

function showErrorToast(errorMessage,timer=3000) {

    Swal.fire({
        icon: 'error',
        title: errorMessage,
        showCloseButton: true,
        showConfirmButton: false,
        position:'top-end',
        toast:true,
        timer,


    })
}

function showSuccess(successMessage,timer=3000) {

    Swal.fire({
        icon: 'success',
        title: successMessage,
        showCloseButton: true,
        showConfirmButton: false,
        position:'top-end',
        toast:true,
        timer,


    })
}
