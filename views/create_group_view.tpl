<div class="form">
    <h2>Create a New Group</h2>

    <?php if (isset($message)): ?>
        <p class="color-red"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="/group/create" method="post">
        <div>
            <label for="name">Group Name</label><br>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name_value ?? ''); ?>" required>
        </div>
        <br>
        <div>
            <label for="description">Description</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($description_value ?? ''); ?></textarea>
        </div>
        <br>
        <button type="submit">Create Group</button>
    </form>
</div>