import QuillBetterTable from 'quill-better-table';

Quill.register('modules/better-table', QuillBetterTable);
  // Configuration de la toolbar pour Quill
  var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['clean'],
    ['link', 'image', 'video'],
  ];

  // Gestionnaire personnalisé pour l'insertion d'image
  function imageHandler() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = () => {
      const file = input.files[0];
      if (file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', "{{ csrf_token() }}");

        fetch("{{ route('upload.attachment') }}", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.url) {
            let range = quill.getSelection();
            quill.insertEmbed(range.index, 'image', data.url);
          } else {
            console.error("Erreur d'upload:", data.error);
          }
        })
        .catch(error => console.error('Erreur de requête:', error));
      }
    };
  }

  // Initialisation de l'éditeur Quill
  var quill = new Quill('#editor-container', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: toolbarOptions,
        handlers: {
          image: imageHandler
        }
      },
      table: false,
        'better-table': {
          operationMenu: {
            items: {
              unmergeCells: {
                text: 'Unmerge Cells'
              }
            }
          }
        },
      imageResize: {}
    }
  });

  // Synchronisation du contenu de Quill avec le champ caché lors de la soumission du formulaire
  document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('content').value = quill.root.innerHTML;
  });