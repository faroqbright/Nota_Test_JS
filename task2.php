<!DOCTYPE html>
<html>

<head>
  <title>Data Table</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }

    tr:hover {
      background-color: #f5f5f5;
    }

    button {
      cursor: pointer;
    }
  </style>
</head>

<body>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Datetime</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <!-- Rows will be dynamically added here -->
    </tbody>
  </table>
  <button id="addEntry">Add New Entry</button>

  <script>
    $(document).ready(function() {

      let mockedData = []
      $.ajax({
        url: 'http://localhost/test_task/js/operations.php',
        type: 'POST',
        data: {
          action: 'getAll'
        },
        success: function(response) {
          response = JSON.parse(response)
          mockedData = response.data
          populateTable(mockedData)
        }
      })

      // Function to populate the table with data
      function populateTable(data) {
        const tbody = $('tbody')
        tbody.empty()

        data.forEach((entry) => {
          const row = `
                        <tr>
                            <td>${entry.id}</td>
                            <td class="editable" data-id="${entry.id}">${entry.name}</td>
                            <td>${entry.datetime}</td>
                            <td>
                                <button class="edit" data-id="${entry.id}">Edit</button>
                                <button class="delete" data-id="${entry.id}">Delete</button>
                            </td>
                        </tr>
                    `

          tbody.append(row)
        })
      }



      let addnew = true;
      // Add New Entry button click event
      $('#addEntry').click(function() {
        $(this).attr('disabled', true)

        const newRow = `
          <tr>
          <td></td>
          <td><input type="text" id="newName"></td>
          <td></td>
          <td><button class="send">Send</button></td>
          </tr>
          `
        $('tbody').prepend(newRow)



        // save new entry
        $('.send').click(function() {
          const newName = $('#newName').val()
          $(this).closest('tr').remove()


          $.ajax({
            url: 'http://localhost/test_task/js/operations.php',
            type: 'POST',
            data: {
              name: newName,
              action: 'add'
            },
            success: function(response) {
              $('#addEntry').attr('disabled', false)
              response = JSON.parse(response);
              // Assuming the response contains the new ID
              const id = response.id
              const name = response.name
              const datetime = response.datetime

              // Add the new row to the table
              const newRow = `
                                <tr>
                                    <td>${id}</td>
                                    <td class="editable" data-id="${id}">${name}</td>
                                    <td>${datetime}</td>
                                    <td>
                                        <button class="edit" data-id="${id}">Edit</button>
                                        <button class="delete" data-id="${id}">Delete</button>
                                    </td>
                                </tr>
                            `

              $('tbody').prepend(newRow)

              // Remove the input field and Send button
              $(this).closest('tr').remove()
            },
            error: function(err) {
              // Handle error
              alert('Error: ' + err.responseText)
            },
          })
        })
      })

      // Edit button click event
      $(document).on('click', '.edit', function() {
        const row = $(this).closest('tr')
        const nameCell = row.find('.editable')
        const editButton = row.find('.edit')

        if (editButton.text() === 'Edit') {
          nameCell.attr('contenteditable', 'true')
          editButton.text('Save')
        } else {
          // Get the edited name
          const newName = nameCell.text()
          const id = nameCell.data('id')

          // Mocked request (replace with an actual request to update the entry)
          $.ajax({
            url: 'http://localhost/test_task/js/operations.php',
            type: 'POST',
            data: {
              id: id,
              name: newName,
              action: 'edit'
            },
            success: function() {
              // Update the name cell
              nameCell.attr('contenteditable', 'false')
              editButton.text('Edit')
            },
            error: function(err) {
              // Handle error
              alert('Error: ' + err.responseText)
            },
          })
        }
      })

      // Delete button click event
      $(document).on('click', '.delete', function() {
        if (confirm('Are you sure you want to delete this entry?')) {
          const row = $(this).closest('tr')
          const id = row.find('.editable').data('id')

          // Mocked request (replace with an actual request to delete the entry)
          $.ajax({
            url: 'http://localhost/test_task/js/operations.php',
            type: 'POST',
            data: {
              id: id,
              action: 'delete'
            },
            success: function() {
              // Remove the row from the table
              row.remove()
            },
            error: function(err) {
              // Handle error
              alert('Error: ' + err.responseText)
            },
          })
        }
      })
    })
  </script>
</body>

</html>