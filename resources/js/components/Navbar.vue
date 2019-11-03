<template>
  <nav class="navbar">
    <RouterLink class="navbar__brand" to="/">
      Picshare
    </RouterLink>
    <div class="navbar__menu">
      <div class="navbar_item">
        <button class="button">
          <i class="icon ion-md-add"></i>
          Submit a photo
        </button>
      </div>
      <span class="navbar_item">
        username
      </span>
      <div class="navbar_item">
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
    </div>
  </nav>
</template>
