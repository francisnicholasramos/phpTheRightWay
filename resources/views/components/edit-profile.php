<?php require_once __DIR__ . '/../layouts/Header.php'; ?>
<?php require_once __DIR__ . '/../layouts/Sidebar.php'; ?>

<div class="edit-profile">
    <div class="component-info-header">
        <p>Edit profile</p>
    </div>
    <div class="edit-profile-field">

    <?php if ($_SERVER['REQUEST_URI'] === '/profiles/' . htmlspecialchars($user->id)): ?>
        <a href="/profiles/<?= htmlspecialchars($user->id) ?>/name">Name</a>
        <a href="/profiles/<?= htmlspecialchars($user->id) ?>/about_me">About me</a>
        <a href="/profiles/<?= htmlspecialchars($user->id) ?>/personal_details">Personal details</a>
        <a href="/profiles/<?= htmlspecialchars($user->id) ?>/education">Education</a>
        <a href="/profiles/<?= htmlspecialchars($user->id) ?>/work">Work</a>
    <?php endif; ?>
    
    <!-- name -->
    <?php if ($_SERVER['REQUEST_URI'] === '/profiles/' . htmlspecialchars($user->id) . '/name'): ?>
      <form action="/profiles/<?= htmlspecialchars($user->id)?>/name" method="POST">
          <div class="info-section">
              <p>Your name</p>
              <div class="info-row">
                  <span class="label">First name:</span>
                  <input type="text" name="first_name" class="value" value="<?= htmlspecialchars($user->first_name) ?>" />
              </div>
              <div class="info-row">
                  <span class="label">Middle name:</span>
                  <input type="text" name="middle_name" class="value" value="<?= htmlspecialchars($user->middle_name ?? '') ?>" />
              </div>
              <div class="info-row">
                  <span class="label">Last name:</span>
                  <input type="text" name="last_name" class="value" value="<?= htmlspecialchars($user->last_name) ?>" />
              </div>
              <div class="info-row">
                  <span class="label">Password:</span>
                  <input type="password" name="password_authority" class="value" placeholder="Enter password to confirm"/>
              </div>
              <div class="info-row">
                  <?php include __DIR__ . '/../layouts/ErrorMessage.php'; ?>
              </div>
              <div class="info-row">
                <small>If you change your name, you can't change it again for 30 days.</small>
              </div>
              <div class="info-row">
                  <button type="submit" class="btn-save">Change name</button>
              </div>
          </div>
      </form>
    <?php endif; ?>

    <!-- personail details -->
    <?php if ($_SERVER['REQUEST_URI'] === '/profiles/' . htmlspecialchars($user->id) . '/personal_details'): ?>
      <form action="/profiles/<?= htmlspecialchars($user->id)?>/personal_details" method="POST">
          <div class="info-section">
              <p>Personal details</p>
              <div class="info-row">
                  <span class="label">Bio:</span>
                  <textarea name="bio" placeholder="Describe yourself" maxlength="150"><?= htmlspecialchars($user->bio) ?></textarea>
              </div>
              <div class="info-row">
                  <span class="label">Username:</span>
                  <input type="text" name="username" value="<?= htmlspecialchars($user->username) ?>" />
              </div>
              <div class="info-row">
                  <span class="label">Hometown:</span>
                  <input type="search" name="hometown" class="value" value="<?= htmlspecialchars($user->hometown ?? '') ?>" placeholder="e.g, Manila"/>
              </div>
              <div class="info-row">
                  <span class="label">Birthday:</span>
                  <input type="date" name="birthday" class="value" value="<?= htmlspecialchars($user->birthday ?? '') ?>" />
              </div>
              <div class="info-row">
                  <span class="label">Gender:</span>
                  <select name="gender" class="value">
                      <option value="male" <?= $user->gender === 'male' ? 'selected' : '' ?>>Male</option>
                      <option value="female" <?= $user->gender === 'female' ? 'selected' : '' ?>>Female</option>
                  </select>
              </div>
              <div class="info-row">
                  <?php include __DIR__ . '/../layouts/ErrorMessage.php'; ?>
              </div>
              <div class="info-row">
                  <button type="submit">Save changes</button>
              </div>
          </div>
      </form>
    <?php endif; ?>

    <!-- about me -->
    <?php if ($_SERVER['REQUEST_URI'] === '/profiles/' . htmlspecialchars($user->id) . '/about_me'): ?>
      <form action="/profiles/<?= htmlspecialchars($user->id)?>/about_me" method="POST">
          <div class="info-section">
              <p>About me</p>
              <div class="info-row">
                  <span class="label">Interests:</span>
                  <input type="text" name="interests" class="value" value="<?= htmlspecialchars($profile ? implode(', ', $profile->interests) : '') ?>" placeholder="e.g. hiking, cooking" />
              </div>
              <div class="info-row">
                  <span class="label">Hobbies:</span>
                  <input type="text" name="hobbies" class="value" value="<?= htmlspecialchars($profile ? implode(', ', $profile->hobbies) : '') ?>" placeholder="e.g. reading, gaming" />
              </div>
              <div class="info-row">
                  <span class="label">Favorite Music:</span>
                  <input type="text" name="favorite_music" class="value" value="<?= htmlspecialchars($profile ? implode(', ', $profile->favorite_music) : '') ?>" placeholder="e.g. Linkin Park, My Chemical Romance" />
              </div>
              <div class="info-row">
                  <button type="submit">Save changes</button>
              </div>
          </div>
      </form>
    <?php endif; ?>

    <!-- education -->
    <?php if ($_SERVER['REQUEST_URI'] === '/profiles/' . htmlspecialchars($user->id) . '/education'): ?>
        <form action="/profiles/<?= htmlspecialchars($user->id)?>/education" method="POST">
          <div class="info-section">
              <p>Education Info</p>
              <div class="info-row">
                  <span class="label">School:</span>
                  <input type="text" name="education[school]" class="value" value="<?= htmlspecialchars($profile?->education['school'] ?? '') ?>" placeholder="e.g, Harvard"/>
              </div>
              <div class="info-row">
                  <span class="label">Degree:</span>
                  <input type="text" name="education[degree]" class="value" value="<?= htmlspecialchars($profile?->education['degree'] ?? '') ?>" placeholder="e.g, Bachelor's Degree"/>
              </div>
              <div class="info-row">
                  <span class="label">Field of Study:</span>
                  <input type="text" name="education[field]" class="value" value="<?= htmlspecialchars($profile?->education['field'] ?? '') ?>" placeholder="e.g, Computer Science"/>
              </div>
              <div class="info-row">
                  <span class="label">From:</span>
                  <input type="number" name="education[from_year]" class="value" value="<?= htmlspecialchars($profile?->education['from_year'] ?? '') ?>" min="1900" max="2099"/>
              </div>
              <div class="info-row">
                  <span class="label">To:</span>
                  <input type="number" name="education[to_year]" class="value" value="<?= htmlspecialchars($profile?->education['to_year'] ?? '') ?>" min="1900" max="2099" />
              </div>
              <div class="info-row">
                  <button type="submit">Save changes</button>
              </div>
          </div>
      </form>
    <?php endif; ?>

    <!-- work -->
    <?php if ($_SERVER['REQUEST_URI'] === '/profiles/' . htmlspecialchars($user->id) . '/work'): ?>
      <form action="" method="">
         <div class="info-section">
                <p>Work Info</p>
                <div class="info-row">
                    <span class="label">Company:</span>
                    <input type="text" name="work[company]" class="value" value="<?= htmlspecialchars($profile?->work['company'] ?? '') ?>" placeholder="Name of company"/>
                </div>
                <div class="info-row">
                    <span class="label">Position:</span>
                    <input type="text" name="work[position]" class="value" value="<?= htmlspecialchars($profile?->work['position'] ?? '') ?>" placeholder="role"/>
                </div>
                <div class="info-row">
                    <span class="label">From:</span>
                    <input type="number" name="work[from_year]" class="value" value="<?= htmlspecialchars($profile?->work['from_year'] ?? '') ?>" min="1900" max="2099" />
                </div>
                <div class="info-row">
                    <span class="label">To:</span>
                    <input type="number" name="work[to_year]" class="value" value="<?= htmlspecialchars($profile?->work['to_year'] ?? '') ?>" min="1900" max="2099" />
                </div>
            </div>
      </form>
    <?php endif; ?>

    </div>
</div>
