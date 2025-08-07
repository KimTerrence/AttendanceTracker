<form action="../controllers/create_class.php" method="POST">
          <h2>Create Class</h2>
            <label for="className">Class Name</label>
            <input type="text" id="className" name="className" placeholder="E.g. Math - Grade 10" required />

            <label for="classCode">Class Code</label>
            <input type="text" id="classCode" name="classCode" placeholder="E.g. MTH10A" required />

            <label for="capacity">Capacity</label>
            <input type="number" id="capacity" name="capacity" min="1" max="100" placeholder="Number of students" required />

            <button type="submit">Create Class</button>
        </form>