import { AsyncStorage, Alert } from "react-native";

import {
  rootUrl,
  apiUrl
} from '../api/settings.js';

export async function loadPosts (dispatch, type) {
  try {
    const value = await AsyncStorage.getItem("pharmapp-" + type);
    if (value !== null) {
      const json = JSON.parse(value)
      handleSuccess(dispatch, type, json)
      return
    } else {
      console.log("loadPosts failed, fetching posts of type", type)
      fetchAll(dispatch)
      return
    }
  } catch (error) {
    console.log("Error loading posts from AsyncStorage : ", error)
  }
}

async function savePosts (type, posts) {
  try {
    await AsyncStorage.setItem("pharmapp-" + type, JSON.stringify(posts));
    console.log("Saved " + posts.length + " posts of type " + type)
  } catch (error) {
    console.log("Error saving posts from AsyncStorage : ", error)
  }
}

export function fetchAll (dispatch, callback) {
  dispatch({
    type: "LOADING_BEGIN",
    postType: "all"
  })
  fetchWPApi(dispatch, "posts", 1, [], callback)
  .then((posts) => {
    handleSuccess(dispatch, "posts", posts, true)
  })
  fetchWPApi(dispatch, "aromatherapie", 1, [], callback)
  .then((posts) => {
    handleSuccess(dispatch, "aromatherapie", posts, true)
  })
  fetchWPApi(dispatch, "phytotherapie", 1, [], callback)
  .then((posts) => {
    handleSuccess(dispatch, "phytotherapie", posts, true)
  })
}

export function fetchPosts (dispatch, type, callback) {
  dispatch({
    type: "LOADING_BEGIN",
    postType: type
  })
  fetchWPApi(dispatch, type, 1, [], callback)
  .then((posts) => {
    handleSuccess(dispatch, type, posts, true)
  })
}

export function fetchWPApi (dispatch, type, page = 1, posts, callback) {
  return new Promise((resolve, reject) => fetch(getUrl(type, page))
    .then(response => {
      if (response.status !== 200)  {
        handleError(dispatch)
        throw `${response.status}: ${response.statusText}`
      }
      response.json().then(data => {
        posts = posts.concat(data);

        total = response.headers.map["x-wp-total"]
        totalPages = response.headers.map["x-wp-totalpages"]

        if(posts.length < total || page < totalPages) {
          callback && callback({
            page: page,
            totalPages: totalPages,
            posts: posts
          });
          fetchWPApi(dispatch, type, page + 1, posts, callback).then(resolve).catch(reject)
        } else {
          resolve(posts);
        }
      }).catch(reject);
  }).catch(reject));
}

export function getUrl (type, page) {
  var order = ""
  if (type === "aromatherapie" || type === "phytotherapie") {
    order = "&orderby=slug&order=asc"
  }
  return rootUrl + apiUrl + type + "?per_page=5&page=" + page + order + "&_embed"
}

function handleSuccess (dispatch, type, value, save = false) {
  console.log("Loaded/Fetched " + value.length + " posts of type " + type)
  if (save) {
    savePosts(type, value)
  }
  dispatch({
    type: "LOADING_SUCCESS",
    postType: type,
    value: value
  })
}

function handleError (dispatch) {
  dispatch({
    type: 'LOADING_FAILURE'
  })
  Alert.alert(
    "Contenu non chargé",
    "Problème lors du chargement",
    [
      {
        text: 'Réessayer',
        onPress: () => callback
      },
      {
        text: 'Fermer',
        style: 'cancel',
      }
    ]
  )
}
