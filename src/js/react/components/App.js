import React from 'react';
import { connect } from 'react-redux';
import ListView from './ListView';

class App extends React.Component {
  constructor () {
    super()
  }

  componentDidMount () {
    /*this.props.dispatch ({
      type: "LOADING_BEGIN",
      postType: 'aromatherapie',
      value: []
    })*/
  }

  test (dispatch) {
    dispatch ({
      type: "LOADING_SUCCESS",
      value: []
    })
  }

  render () {
    console.log("App Render", this.props)
    return (
      <div className="row">
        <div className="col-sm-4">
          {
            this.props.posts.length > 0 ?
            <ListView data={this.props.posts} />
            : null
          }
        </div>
        <div className="col-sm-8" style={{height: "100em"}} onClick={() => this.test(this.props.dispatch)}></div>
      </div>
    )
  }
}

const mapStateToProps = (state) => {
  console.log("MapState", state)
  return {
    posts: state.aromaPosts
  }
}

export default connect(mapStateToProps)(App)
