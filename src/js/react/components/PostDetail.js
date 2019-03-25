import React from "react";

export default class PostDetail extends React.Component {
  render () {
    //console.log(this.props)
    return (
      <div className="card">
        <div className="card-body">
          {
            this.props.post ? <div>Title: {this.props.post.title.rendered}</div> : null
          }
        </div>
      </div>
    )
  }
}
