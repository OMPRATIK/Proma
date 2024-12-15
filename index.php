<?php
require 'db.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ProMa - Personal project manager</title>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" href="index.css">
</head>
<body>

  <div class="modal"></div>
  <div class="container">
  <nav class="nav">
    <div class="logo-container">
      <img src="./img/logo.png" alt="" class="logo">
      <h1 class="logo-name">pro-ma</h1>
    </div>
    <div>
      <p class="user">Hi, Pratik 👋</p>
    </div>
  </nav>
  <div class="grid">

    <form action="add.php" method="POST" class="form">
      <h2>Add Project ➕</h2>
      <input type="text" placeholder="project name..." name="name">
      <input type="text" placeholder="github link..." name="link">
      <select name="status" id="">
        <option value="3">Not started</option>
      <option value="1">Working</option>
      <option value="2">Completed</option>
    </select>
    <textarea placeholder="project description..."  name="description" rows="3"></textarea>

    <button type="submit" class="btn-submit">Add &nbsp; <span>&#43;</span></button>
  </form>

  <div class="ai">
    <h2>Generate Project Ideas 🧠</h2>
    <button class="btn-generate">Generate With AI ✨</button>

    <form class="idea-container" action="addIdea.php" method="POST">
      <h3 class="idea-title"></h3>
      <input type="hidden" name="ideaName" id="idea-name">
      <input type="hidden" name="ideaDescription" id="idea-description">
      <input type="hidden" name="link" value="">
      <input type="hidden" name="status" value="3">
      <p class="idea"></p>
      <button type="submit" class="add-idea">Add Idea &nbsp; <span>💡</span></button>
    </form>

  </div>
</div>

  <?php
      $projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
    ?>

  <section class="project-section">
    <h2>Your Projects 📚</h2>
    <?php if($projects->rowCount() <= 0) { ?>
        <div class="no-projects">No Projects</div>
    <?php } ?>
    <div class="project-container"> 
      <?php foreach($projects as $project) { ?>
        <div class="project">
          <div class="project-header">
            <h3 class="project-name"><?php echo $project['name']?></h3>
            <p class="project-status"><?php
              if($project['status'] == 1) {
                echo '<span class="status-w">Working</span>';
              } elseif($project['status'] == 2) {
                echo '<span class="status-c">Completed</span>';
              } else {
                echo '<span class="status-n">Not started</span>';
              }
            ?>
            </p>
          </div>
          <div class="project-link">
            <span>🔗</span>
            <a href="<?php echo $project['link']; ?>" class="github-link" target="_blank">Github link</a>
          </div>
          <p class="project-description">
            <?php echo $project['description']?>
          </p>
          <div class="last-container">

            <div class="btn-edit-delete">
              <button class="btn-edit">
                Edit
                <ion-icon name="create-outline"></ion-icon>
              </button>
              <button class="btn-delete" id="<?php echo $project["id"]?>">
                Delete
                <ion-icon name="trash-outline"></ion-icon> 
              </button>
            </div>

            <p class="date"><?php echo $project["date_time"]?></p>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>
  </div>
  
  <script type="module">
      import { GoogleGenerativeAI } from "https://esm.run/@google/generative-ai";
      const API_KEY = "Replace with your gemini key!";

      const genAI = new GoogleGenerativeAI(API_KEY);
      const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

      const prompt = "Give me one random computer science project idea which will have a title and a small description of 10-20 words. The first word of response must me be the title name and the rest of the response will be the description of the project.";
      async function generate() {
          try {
              const result = await model.generateContent(prompt);
              return result.response.text();
          } catch (error) {
              console.error('Error:', error);
          }
      }
      const ideaTitle = document.querySelector('.idea-title');
      const idea = document.querySelector('.idea');
      const ideaNameInput = document.getElementById('idea-name');
      const ideaDescriptionInput = document.getElementById('idea-description');

      const init = await generate();
      const[title, description] = init.split(":");
        ideaTitle.textContent = title;
        idea.textContent = description;
        ideaNameInput.value = title;
        ideaDescriptionInput.value = description;

      const btnGenerate = document.querySelector('.btn-generate');
      btnGenerate.addEventListener('click',async () => {
        const data = await generate()
        const[title, description] = data.split(":");
        ideaTitle.textContent = title;
        idea.textContent = description;

        ideaNameInput.value = title;
        ideaDescriptionInput.value = description;
        console.log(ideaNameInput.value, ideaDescriptionInput.value )
      });
  </script>

  <script>
    const btnsDelete = document.querySelectorAll('.btn-delete');
    btnsDelete.forEach(btn => {
      btn.addEventListener('click', () => {
        const projectId = btn.id;
        if(confirm("Are you sure you want to delete this project?")) {
        console.log("Deleting project");
        fetch(`delete.php?id=${projectId}`, {
          method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
          console.log(data);
          location.reload();
        })
        .catch(error => console.error('Error:', error));
      }
      });
    });

    const modal = document.querySelector('.modal');
    const btnEdit = document.querySelectorAll('.btn-edit');
    btnEdit.forEach(btn => {
      btn.addEventListener('click', () => {
        const projectContainer = btn.closest('.project');
        const projectId = projectContainer.querySelector('.btn-delete').id;
        const projectName = projectContainer.querySelector('.project-name').textContent;
        const projectStatus = projectContainer.querySelector('.project-status').textContent;
        const projectLink = projectContainer.querySelector('.github-link').href;
        const projectDescription = projectContainer.querySelector('.project-description').textContent;
        console.log(projectId)
        modal.innerHTML = `
          <form class="form form-edit" action="edit.php" method="POST">
            <div class="edit-close">
              <h2>Edit Project</h2>
              <span class="close">&times;</span>
            </div>
            <input type="hidden" name="id" value="${projectId}">
            <input type="text" placeholder="project name..." name="name" value="${projectName}">
            <input type="text" placeholder="github link..." name="link" value="${projectLink}">
            <select name="status" id="editProjectStatus">
              <option value="0" ${projectStatus === 'Not started' ? 'selected' : ''}>Not started</option>
              <option value="1" ${projectStatus === 'Working' ? 'selected' : ''}>Working</option>
              <option value="2" ${projectStatus === 'Completed' ? 'selected' : ''}>Completed</option>
            </select>
            <textarea placeholder="project description..." name="description" rows="3">${projectDescription}</textarea>
            <button class="btn-submit">Edit &nbsp; <ion-icon name="create-outline"></ion-icon>  </button>
          </form>
        `;
        modal.style.display = 'flex';

        // Attach the event listener to the close button after the modal content is added
        const closeModal = document.querySelector('.close');
        closeModal.addEventListener('click', () => {
          modal.style.display = 'none';
        });
      });
    });
  </script>
</body>
</html>