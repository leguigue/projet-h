document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('projectForm');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    imageInput.addEventListener('change', previewImage);
    form.addEventListener('submit', handleFormSubmit);
});

function previewImage(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '200px';
            img.style.maxHeight = '200px';
            imagePreview.innerHTML = '';
            imagePreview.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    console.log("Formulaire soumis");    
    errorMessage.textContent = '';
    successMessage.textContent = '';
    const submitButton = e.target.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Chargement...';
    const formData = new FormData(e.target);
    try {
        console.log("Début de l'upload de l'image");
        const uploadResponse = await uploadImage(formData);
        console.log("Réponse de l'upload:", uploadResponse);
        if (!uploadResponse.success) {
            throw new Error(uploadResponse.error);
        }
        document.getElementById('image_path').value = uploadResponse.path;
        console.log("Début de la soumission du formulaire");
        const submitResponse = await submitForm(new FormData(e.target));
        console.log("Réponse de la soumission:", submitResponse);
        if (submitResponse.success) {
            successMessage.textContent = submitResponse.message || "Projet ajouté avec succès!";
            e.target.reset();
            imagePreview.innerHTML = '';
        } else {
            throw new Error(submitResponse.error);
        }
    } catch (error) {
        console.error('Erreur:', error);
        errorMessage.textContent = error.message || 'Une erreur est survenue.';
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Ajouter le projet';
    }
}

function uploadImage(formData) {
    console.log('Uploading image...');
    return fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Upload response status:', response.status);
        return handleResponse(response);
    })
    .then(data => {
        console.log('Upload response data:', data);
        return data;
    })
    .catch(error => {
        console.error('Error in uploadImage:', error);
        throw error;
    });
}

function handleResponse(response) {
    return response.text().then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON:', text);
            throw new Error('Invalid server response');
        }
    }).then(data => {
        if (!data.success) {
            throw new Error(data.error || 'Unknown error');
        }
        return data;
    });
}

function submitForm(formData) {
    return fetch('processproject.php', {
        method: 'POST',
        body: formData
    })
    .then(handleResponse)
    .catch(error => {
        console.error('Error in submitForm:', error);
        throw error;
    });
}