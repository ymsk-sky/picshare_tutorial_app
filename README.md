# Vue練習

- Vue + Vue Router + Vuex + Laravelが学べる
- SPA開発の練習になる
- 実際に動作する

# Docker練習

- docker-compose.yml, Dockerfileの書き方に慣れる

# 開発環境

- Node: ~~10.7.0~~ -> 10.17.0
- npm: ~~6.4.1~~ -> 6.11.3
- Vue.js: 2.5.21
- Vue Router: 3.0.2
- Vuex: 3.0.1
- PHP: ~~7.2.10~~ -> 7.2.24
- Laravel: ~~5.7.19~~ -> 6.4.1

# つまずきポイント（変更点）

## 4章

### phpunitへのテストDB記述

><env name="DB_CONNECTION" ...省略

となっているが

><server name="DB_CONNECTION" ...省略

へ変更

### LoginApiTest.phpのテストコード

>'password' => 'secret'

を

>'password' => 'password'

へ変更

### setUp()メソッドの記述

テストコード内でsetUp()メソッドを使用するときは明示的に引数、返り値を記述する

TestCase.phpに合わせて

>public function setUp(): void

と、返り値はvoid型（返り値なし）と記述する必要あり

## 8章

### システムエラー実装時authストアへsetApiStatusの実装の説明がない

```javascript:auth.js
const mutations = {
  setUser (state, user) {/* 略 */},
  setApiStatus (state, status) {
    state.apiStatus = apiStatus
  },
  setLoginErrorMessages (state, messages) {/* 略 */}
}
```

## 9章

### Photo.phpのコンストラクタにて使用のarray_get()はLaravel 5.8から非推奨になった

以下の措置をとる

> use Illuminate\Support\Arr;

>array_get()をArr:get()へ置き換える

## 11章

### Photo.phpに以下を追加

```
use Illuminate\Support\Facades\Storage;
```

### str_random()は非推奨なので以下に変更

```diff
-'id' => str_random(12),
-'filename' => str_random(12) . '.jpg',
+'id' => Str::random(12),
+'filename' => Str::random(12) . '.jpg',
```

## 12章

### Pagination実装時

`PhotoList.vue` 内のscript内のmethods内fetchPhotosメソッド

```diff
async fetchPhotos () {
-  const response = await axios.get(`/api/photos/?page=${this.page}`)
+  const response = await axios.get(`/api/photos/?page=${this.$route.query.page}`)
}
```

### おまけ実装時

Photo.vue内<img>タグ内の `:src` は

`:src="item.filepath"` では画像が表示されない。以下に変更

```
:scr="item.url"
```

# 参考記事
- [Vue + Vue Router + Vuex + Laravel チュートリアル（全16回）を書きました。 - Qiita](https://qiita.com/MasahiroHarada/items/2597bd6973a45f92e1e8)

- [Vue + Vue Router + Vuex + Laravel チュートリアル（全16回）1章](https://www.hypertextcandy.com/vue-laravel-tutorial-introduction)
