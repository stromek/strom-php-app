import React, {useState} from 'react'
import {useNavigate, useParams} from "react-router";


export default (props) => {
  const [count, setCount] = useState(1)
  const {thread} = useParams()

  console.log(thread)

  return (
    <div>

      <h1>{thread}</h1>
      <strong>{count}</strong>

      <button onClick={() => {
        setCount(count + 1)
      }}>INCRESE</button>

      {/*<input value="ALERT" type="button" onClick={() => props.alert(Math.random())} />*/}
    </div>
  )
}
