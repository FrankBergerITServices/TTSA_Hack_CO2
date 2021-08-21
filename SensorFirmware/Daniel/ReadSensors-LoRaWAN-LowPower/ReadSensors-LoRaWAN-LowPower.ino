/*
  MKR ENV Shield - Read Sensors

  This example reads the sensors on-board the MKR ENV shield
  and prints them to the Serial Monitor once a second.

  The circuit:
  - Arduino MKR board
  - Arduino MKR ENV Shield attached

  This example code is in the public domain.
*/

#include <MKRWAN.h>
#include <Arduino_MKRENV.h>
#include "arduino_secrets.h"

LoRaModem modem;

float co2;
float temperature;
float humidity;

void setup() {
  Serial.begin(115200);
  while (!Serial);

 if (!ENV.begin()) {
    Serial.println("Failed to initialize MKR ENV shield!");
    while (1);
  }  

  // change this to your regional band (eg. US915, AS923, ...)
  if (!modem.begin(EU868)) {
    Serial.println("Failed to start module");
    while (1) {}
  };

  connectToLoRaWAN();
}

void connectToLoRaWAN(){
  Serial.println("Connecting...");
  int connected = modem.joinOTAA(APP_EUI, APP_KEY);

  if (!connected) {
    Serial.println("Something went wrong; are you indoor? Move near a window and retry");
    while (1) {}
  }

  delay(5000);
}

void sendSensorValues(){
  Serial.println("Sending message...");
  modem.setPort(3);
  modem.beginPacket();
  modem.write<float>(co2);
  modem.write<float>(temperature);
  modem.write<float>(humidity);
    
  int error = modem.endPacket(true);
  
  if (error > 0) {
    Serial.println("Message sent correctly!");
  } else {
    Serial.println("Error sending message :(");
  }

  Serial.println();
}

void loop() {
  // read all the sensor values
  co2         = 450;
  temperature = ENV.readTemperature();
  humidity    = ENV.readHumidity();

  // print each of the sensor values
  Serial.print("CO2 = ");
  Serial.print(co2);
  Serial.println(" ppm");
  
  Serial.print("Temperature = ");
  Serial.print(temperature);
  Serial.println(" °C");

  Serial.print("Humidity    = ");
  Serial.print(humidity);
  Serial.println(" %");
  
  // print an empty line
  Serial.println();

  sendSensorValues();

  // wait 1 second to print again
  delay(300000);
}
