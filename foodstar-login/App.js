import * as React from 'react';
import {
  Text,
  TextInput,
  View,
  StyleSheet,
  Image,
  ImageBackground,
  TouchableOpacity,
  KeyboardAvoidingView,
} from 'react-native';
import Constants from 'expo-constants';

export default class App extends React.Component {
  state = {
    email: '',
    password: '',
  };
  handleEmail = text => {
    this.setState({ email: text });
  };
  handlePassword = text => {
    this.setState({ password: text });
  };
  login = (email, pass) => {
      alert('email: ' + email + ' password: ' + pass)
  };
  render() {
    return (
      <KeyboardAvoidingView behavior = "padding" style={styles.container}>
        <ImageBackground
          resizeMode={'cover'}
          style={{ flex: 1, opacity: 0.8 }}
          source={require('food_background.png')}>
          <Text style={styles.foodstar}>FoodStar</Text>
          <TextInput
            style={styles.inputEmail}
            placeholder="email"
            placeholderTextColor="#FFFFFF"
            onChangeText = {this.handleEmail}
          />
          <TextInput
            style={styles.inputPassword}
            placeholder="password"
            placeholderTextColor="#FFFFFF"
            secureTextEntry
            onChangeText = {this.handlePassword}
          />
          <TouchableOpacity
          onPress = {
                  () => this.login(this.state.email, this.state.password)
          }>
            <Text style = {styles.loginButton}>LOGIN</Text>
          </TouchableOpacity>
        </ImageBackground>
      </KeyboardAvoidingView>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    paddingTop: Constants.statusBarHeight,
    backgroundColor: '#ecf0f1',
    padding: 8,
  },
  foodstar: {
    width: 533,
    height: 224,
    paddingLeft: 65,
    color: '#ffffff',
    fontFamily: 'Josefin Sans',
    fontSize: 52,
    fontWeight: '700',
    lineHeight: 250,
  },
  inputEmail: {
    width: 223,
    height: 44,
    borderRadius: 9,
    borderColor: '#000000',
    borderStyle: 'solid',
    backgroundColor: 'rgba(200,200,200,0.4)',
    borderWidth: 2,
    opacity: 0.44,
    marginTop: 150,
    marginLeft: 65,
  },
  inputPassword: {
    width: 223,
    height: 44,
    borderRadius: 9,
    borderColor: '#000000',
    borderStyle: 'solid',
    backgroundColor: 'rgba(200,200,200,0.4)',
    borderWidth: 2,
    opacity: 0.44,
    marginTop:10,
    marginLeft: 65,
  },
  loginButton: {
    width: 223,
    height: 44,
    borderRadius: 9,
    backgroundColor: '#5663ff',
    borderStyle: 'solid',
    borderWidth: 2,
    marginTop:10,
    marginLeft: 65,
    textAlign: 'center',
    color: '#ffffff',
    fontFamily: 'Josefin Sans',
    fontSize: 20,
    fontWeight: '400',
    lineHeight: 42,
  }
});
