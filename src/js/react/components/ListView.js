import React from 'react';
import { connect } from 'react-redux';
import { RenderHTML } from './RenderHTML';

class ListView extends React.Component {

  constructor() {
    super()
    this.state = {
      active: null
    }
  }

  _itemSelected (id) {
    this._currentSection = null
    this.props.dispatch({
      type: "SET_ACTIVE",
      value: id
    })
  }

  _isSection (item) {
    if (item) {
      if (item.title.rendered.charAt(0) !== this._currentSection) {
        this._currentSection = item.title.rendered.charAt(0)
        return true
      } else {
        return false
      }
    } else {
      return (
        <li className="list-group-item disabled" key={this._currentSection}>
          {
            this._currentSection
          }
        </li>
      )
    }
  }

  _renderItem (item, index) {
    return (
      <li
        className={
          item.id === this.props.activePost ?
           "list-group-item list-group-item-action active"
           : "list-group-item list-group-item-action"
        }
        key={ index }
        onClick={ this._itemSelected.bind(this, item.id) }
        >
        {
          RenderHTML(item.title.rendered)
        }
      </li>
    )
  }

  render () {
    return (
      <ul className="list-group">
        {
          this.props.data ? this.props.data.map((item, index) =>
            {
              return this._isSection(item) ?
              [this._isSection(), this._renderItem(item, index)] : this._renderItem(item, index)
            }
          ) : null
        }
      </ul>
    )
  }
}

const mapStateToProps = (state) => {
  //console.log("MapState", state)
  return {
    activePost: state.appReducer.activePost
  }
}

export default connect(mapStateToProps)(ListView)
